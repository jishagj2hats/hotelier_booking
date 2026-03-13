<?php
declare(strict_types=1);
namespace Hotelier\HotelierBooking\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Hotelier\HotelierBooking\Domain\Repository\RoomRepository;
use Hotelier\HotelierBooking\Domain\Repository\BookingRepository;
use Hotelier\HotelierBooking\Service\OfferService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Hotelier\HotelierBooking\Domain\Repository\OfferRepository;

class RoomController extends ActionController
{
    protected RoomRepository $roomRepository;
    protected BookingRepository $bookingRepository;
    protected OfferService $offerService;
    protected OfferRepository $offerRepository;

    public function injectOfferRepository(OfferRepository $offerRepository): void
    {
        $this->offerRepository = $offerRepository;
    }

    public function injectRoomRepository(RoomRepository $roomRepository): void
    {
        $this->roomRepository = $roomRepository;
    }

    public function injectBookingRepository(BookingRepository $bookingRepository): void
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function injectOfferService(OfferService $offerService): void
    {
        $this->offerService = $offerService;
    }

    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $rooms = $this->roomRepository->findAll();
        $site = $this->request->getAttribute('site');
        $pageUid = $this->request->getAttribute('routing')->getPageId();

        // ✅ Get full page record
        $pageRepository = GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Domain\Repository\PageRepository::class
        );
        $currentPage = $pageRepository->getPage((int) $pageUid);

        $this->view->assignMultiple([
            'rooms' => $rooms,
            'site' => $site,
            'filterData' => [],
            'currentPage' => $currentPage,
            'offerPrices' => $this->buildOfferPrices($rooms->toArray()),
            'priceBreakdowns' => $this->buildPriceBreakdowns($rooms->toArray()),
            'activityMessages' => $this->buildActivityMessages($rooms->toArray()),
        ]);

        return $this->htmlResponse();
    }

    public function filterAction(): \Psr\Http\Message\ResponseInterface
    {
        $filterData = $this->request->getArguments();
        $site = $this->request->getAttribute('site');

        // Parse dates
        $checkin = !empty($filterData['checkin']) ? $filterData['checkin'] : null;
        $checkout = !empty($filterData['checkout']) ? $filterData['checkout'] : null;
        $adults = !empty($filterData['adults']) ? (int) $filterData['adults'] : 0;
        $children = !empty($filterData['children']) ? (int) $filterData['children'] : 0;

        $rooms = $this->roomRepository->filterRooms(
            $checkin,
            $checkout,
            $adults,
            $children
        );
        $checkinTimestamp = !empty($filterData['checkin']) ? strtotime($filterData['checkin']) : null;
        $this->view->assignMultiple([
            'rooms' => $rooms,
            'site' => $site,
            'filterData' => $filterData,
            'offerPrices' => $this->buildOfferPrices($rooms, $checkinTimestamp),
            'priceBreakdowns' => $this->buildPriceBreakdowns($rooms, $checkinTimestamp),
            'activityMessages' => $this->buildActivityMessages($rooms),
        ]);
        return $this->htmlResponse();
    }

    public function showAction(\Hotelier\HotelierBooking\Domain\Model\Room $room): \Psr\Http\Message\ResponseInterface
    {
        $site = $this->request->getAttribute('site');
        $offerPrices = $this->buildOfferPrices([$room]);
        $priceBreakdowns = $this->buildPriceBreakdowns([$room]);

        $this->view->assignMultiple([
            'room' => $room,
            'site' => $site,
            'offerPrice' => $offerPrices[$room->getUid()] ?? null,
            'priceBreakdown' => $priceBreakdowns[$room->getUid()] ?? null,
        ]);
        return $this->htmlResponse();
    }

    public function bookingAction(int $room): \Psr\Http\Message\ResponseInterface
    {
        $selectedRoom = $this->roomRepository->findByUid($room);
        $this->view->assign('room', $selectedRoom);
        return $this->htmlResponse();
    }
    public function ajaxFilterAction(): \Psr\Http\Message\ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $site = $this->request->getAttribute('site');

        // AJAX request payload is namespaced: tx_hotelierbooking_rooms[...]
        $filterData = $arguments['tx_hotelierbooking_rooms'] ?? $arguments;

        $checkin = !empty($filterData['checkin']) ? $filterData['checkin'] : null;
        $checkout = !empty($filterData['checkout']) ? $filterData['checkout'] : null;
        $adults = !empty($filterData['adults']) ? (int) $filterData['adults'] : 0;
        $children = !empty($filterData['children']) ? (int) $filterData['children'] : 0;

        $rooms = $this->roomRepository->filterRooms(
            $checkin,
            $checkout,
            $adults,
            $children
        );
        $checkinTimestamp = !empty($filterData['checkin']) ? strtotime($filterData['checkin']) : null;
        $this->view->assignMultiple([
            'rooms' => $rooms,
            'site' => $site,
            'filterData' => $filterData,
            'offerPrices' => $this->buildOfferPrices($rooms, $checkinTimestamp),
            'priceBreakdowns' => $this->buildPriceBreakdowns($rooms, $checkinTimestamp),
            'activityMessages' => $this->buildActivityMessages($rooms),
        ]);

        return $this->htmlResponse();
    }
    private function buildOfferPrices(array $rooms, ?int $checkinTimestamp = null): array
    {
        $timestamp = $checkinTimestamp ?? time();
        $offerPrices = [];
        foreach ($rooms as $room) {
            $bestOffer = $this->offerService->findBestOfferForRoom($room, $timestamp);
            $discounted = $this->offerService->calculateDiscountedPrice($room, $bestOffer);
            $offerPrices[$room->getUid()] = $discounted ?? $room->getActiveOfferPriceForDate($timestamp);
        }
        return $offerPrices;
    }

    private function buildPriceBreakdowns(array $rooms, ?int $checkinTimestamp = null): array
    {
        $timestamp = $checkinTimestamp ?? time();
        $breakdowns = [];

        foreach ($rooms as $room) {
            $bestOffer = $this->offerService->findBestOfferForRoom($room, $timestamp);
            $discounted = $this->offerService->calculateDiscountedPrice($room, $bestOffer);
            $activeOffer = $discounted ?? $room->getActiveOfferPriceForDate($timestamp);
            $basePrice = $activeOffer ?? $room->getRent();
            $originalPrice = $room->getRent();
            $discount = max(0, $originalPrice - $basePrice);

            $gstRate = max(0.0, $room->getGstRate());
            $serviceRate = max(0.0, $room->getServiceChargeRate());

            $gstAmount = $basePrice * ($gstRate / 100);
            $serviceChargeAmount = $basePrice * ($serviceRate / 100);
            $total = $basePrice + $gstAmount + $serviceChargeAmount;
            $breakdowns[$room->getUid()] = [
                'base' => $basePrice,
                'original' => $originalPrice,
                'discount' => $discount,
                'gst' => $gstAmount,
                'gstRate' => $gstRate,
                'service' => $serviceChargeAmount,
                'serviceRate' => $serviceRate,
                'total' => $total,
                'currency' => '₹',
            ];
        }

        return $breakdowns;
    }
    public function offerRoomsAction(int $offer): \Psr\Http\Message\ResponseInterface
    {
        $site = $this->request->getAttribute('site');

        // Get the offer
        $offerObject = $this->offerRepository->findByUid($offer);

        if (!$offerObject) {
            // Offer not found → redirect to list
            return $this->redirect('list');
        }

        // Get only rooms linked to this offer
        $rooms = $offerObject->getRooms()->toArray();


        $this->view->assignMultiple([
            'rooms' => $rooms,
            'site' => $site,
            'filterData' => [],
            'activeOffer' => $offerObject,
            'offerPrices' => $this->buildOfferPrices($rooms),
            'priceBreakdowns' => $this->buildPriceBreakdowns($rooms),
        ]);

        return $this->htmlResponse();
    }
    private function buildActivityMessages(array $rooms): array
    {
        $messages = [];
        $now = time();

        foreach ($rooms as $room) {
            $uid = $room->getUid();
            $count = $this->bookingRepository->countBookingsLast24Hours($uid);
            $lastBooked = $this->bookingRepository->getLastBookingTime($uid);

            if ($count >= 3) {
                // High activity — always show
                $messages[$uid] = "Booked {$count} times in the last 24 hours";

            } elseif ($lastBooked) {
                $diffMinutes = (int) (($now - $lastBooked) / 60);

                if ($diffMinutes < 60) {
                    // Under 1 hour — very urgent
                    $messages[$uid] = "Last booked {$diffMinutes} minute" . ($diffMinutes !== 1 ? 's' : '') . " ago";

                } elseif ($diffMinutes < 180) {
                    // Under 3 hours — still urgent
                    $hours = (int) ($diffMinutes / 60);
                    $messages[$uid] = "Last booked {$hours} hour" . ($hours !== 1 ? 's' : '') . " ago";

                }
                // Beyond 3 hours → show nothing, not urgent enough
            }
        }

        return $messages;
    }
}