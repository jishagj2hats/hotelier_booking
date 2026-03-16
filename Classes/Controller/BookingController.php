<?php
namespace Hotelier\HotelierBooking\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Hotelier\HotelierBooking\Domain\Repository\RoomRepository;
use Hotelier\HotelierBooking\Domain\Repository\BookingRepository;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use Hotelier\HotelierBooking\Domain\Model\Booking;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Resource\FileRepository;

class BookingController extends ActionController
{
    public function __construct(
        protected RoomRepository $roomRepository,
        protected BookingRepository $bookingRepository,
        protected PersistenceManagerInterface $persistenceManager,
        protected ResourceFactory $resourceFactory,
        private readonly FileRepository $fileRepository
    ) {
    }

    public function formAction(int $room = null): \Psr\Http\Message\ResponseInterface
    {
        $selectedRoom = null;
        $disabledDates = [];
        $allDatesBlocked = false;

        $room = $this->request->hasArgument('room')
            ? (int) $this->request->getArgument('room')
            : null;

        $site = $this->request->getAttribute('site');
        $settings = $site->getSettings();
        $roomsPageUid = (int) $settings->get('roomsPage');

        // ── FIX: Only show flash messages if redirected after a successful booking.
        // Without this flag, flash messages from submitAction bleed into fresh form loads.
        $showSuccess = $this->request->hasArgument('bookingSuccess')
            && (int) $this->request->getArgument('bookingSuccess') === 1;

        if (!$showSuccess) {
            // Discard any queued flash messages so they don't show on plain form load
            $this->getFlashMessageQueue()->getAllMessagesAndFlush();
        }

        if ($room) {
            $selectedRoom = $this->roomRepository->findByUid($room);

            if ($selectedRoom) {
                $totalUnits = $selectedRoom->getNumberOfRooms();

                $ranges = $this->bookingRepository->findBookedRangesByRoom($room);

                $dateCounts = [];
                foreach ($ranges as $range) {
                    $start = (int) $range['checkin'];
                    $end = (int) $range['checkout'];
                    for ($date = $start; $date < $end; $date += 86400) {
                        $ds = date('Y-m-d', $date);
                        $dateCounts[$ds] = ($dateCounts[$ds] ?? 0) + 1;
                    }
                }

                foreach ($dateCounts as $ds => $count) {
                    if ($count >= $totalUnits) {
                        $disabledDates[] = $ds;
                    }
                }

                if ($totalUnits <= 0) {
                    $allDatesBlocked = true;
                }
            }
        }

        $checkin = $bookingArgs['checkin'] ?? ($this->request->hasArgument('checkin') ? $this->request->getArgument('checkin') : '');
        $checkout = $bookingArgs['checkout'] ?? ($this->request->hasArgument('checkout') ? $this->request->getArgument('checkout') : '');

        $adults = $this->request->hasArgument('adults') ? (int) $this->request->getArgument('adults') : 1;
        $children = $this->request->hasArgument('children') ? (int) $this->request->getArgument('children') : 0;

        $checkinFormatted = $checkin ? date('Y-m-d H:i', strtotime($checkin)) : '';
        $checkoutFormatted = $checkout ? date('Y-m-d H:i', strtotime($checkout)) : '';

        $this->view->assignMultiple([
            'selectedRoom' => $selectedRoom,
            'roomUid' => $room,
            'disabledDates' => array_unique($disabledDates),
            'allDatesBlocked' => $allDatesBlocked,
            'showSuccess' => $showSuccess,
            'redirectPage' => $this->request->hasArgument('redirectPage')
                ? (int) $this->request->getArgument('redirectPage')
                : $roomsPageUid,
            'prefillCheckin' => $checkinFormatted,
            'prefillCheckout' => $checkoutFormatted,
            'prefillAdults' => $adults,
            'prefillChildren' => $children,
        ]);

        return $this->htmlResponse();
    }


    public function submitAction(array $booking, ?int $selectedRoom = null): \Psr\Http\Message\ResponseInterface
    {
        $booking['checkin'] = strtotime($booking['checkin']);
        $booking['checkout'] = strtotime($booking['checkout']);

        $bookingObj = new Booking();
        $bookingObj->setName($booking['name']);
        $bookingObj->setEmail($booking['email']);
        $bookingObj->setCheckin($booking['checkin']);
        $bookingObj->setCheckout($booking['checkout']);
        $bookingObj->setAdults((int) $booking['adults']);
        $bookingObj->setChildren((int) $booking['children']);
        $bookingObj->setMessage($booking['message']);

        $roomUid = (int) ($booking['room'] ?? 0);
        $room = $this->roomRepository->findByUid($roomUid);

        if ($room !== null) {

            // ── 1. Availability check ──────────────────────────
            if ($room->getNumberOfRooms() <= 0) {
                $this->addFlashMessage(
                    'Sorry, this room is no longer available.',
                    'Not Available',
                    \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                );
                return $this->redirect('form', 'Booking', null, [
                    'room' => $roomUid,
                    'bookingSuccess' => 0,
                ]);
            }

            // ── 2. Adults limit ────────────────────────────────
            if ((int) $booking['adults'] > $room->getMaxAdults()) {
                $this->addFlashMessage(
                    'This room allows a maximum of ' . $room->getMaxAdults() . ' adults.',
                    'Too Many Adults',
                    \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                );
                return $this->redirect('form', 'Booking', null, [
                    'room' => $roomUid,
                    'bookingSuccess' => 0,
                ]);
            }

            // ── 3. Children limit ──────────────────────────────
            if ((int) $booking['children'] > $room->getMaxChildren()) {
                $this->addFlashMessage(
                    'This room allows a maximum of ' . $room->getMaxChildren() . ' children.',
                    'Too Many Children',
                    \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                );
                return $this->redirect('form', 'Booking', null, [
                    'room' => $roomUid,
                    'bookingSuccess' => 0,
                ]);
            }

            // ── 4. Total occupancy limit ───────────────────────
            $totalGuests = (int) $booking['adults'] + (int) $booking['children'];
            if ($totalGuests > $room->getMaxOccupancy()) {
                $this->addFlashMessage(
                    'This room allows a maximum of ' . $room->getMaxOccupancy() . ' guests in total.',
                    'Too Many Guests',
                    \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                );
                return $this->redirect('form', 'Booking', null, [
                    'room' => $roomUid,
                    'bookingSuccess' => 0,
                ]);
            }

            // ── 5. Checkin must be before checkout ─────────────
            if ($booking['checkin'] >= $booking['checkout']) {
                $this->addFlashMessage(
                    'Check-out date must be after check-in date.',
                    'Invalid Dates',
                    \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR
                );
                return $this->redirect('form', 'Booking', null, [
                    'room' => $roomUid,
                    'bookingSuccess' => 0,
                ]);
            }

            // ── All checks passed — save booking ───────────────
            $bookingObj->setRoom($room);
            $this->roomRepository->update($room);
        }

        $site = $this->request->getAttribute('site');
        $settings = $site->getSettings();
        $bookingPid = (int) $settings->get('bookingPid');
        $roomsPageUid = (int) $settings->get('roomsPage');

        if ($bookingPid > 0) {
            $bookingObj->_setProperty('pid', $bookingPid);
        }

        $this->bookingRepository->add($bookingObj);
        $this->persistenceManager->persistAll();

        $this->addFlashMessage(
            'Your booking for ' . $room->getTitle() . ' has been confirmed! We will contact you shortly.',
            'Booking Confirmed!',
            \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK
        );

        // ── FIX: Pass bookingSuccess=1 so formAction knows to display the message
        return $this->redirect('form', 'Booking', null, [
            'room' => $roomUid,
            'redirectPage' => $roomsPageUid,
            'bookingSuccess' => 1,
        ]);
    }


    public function reservationAction(): \Psr\Http\Message\ResponseInterface
    {
        $arguments = $this->request->getArguments();
        $rooms = $this->roomRepository->findAll()->toArray();
        $availableRooms = array_filter($rooms, fn($room) => $room->getNumberOfRooms() > 0);

        $this->view->assignMultiple([
            'checkin' => $arguments['checkin'] ?? '',
            'checkout' => $arguments['checkout'] ?? '',
            'adult' => $arguments['adult'] ?? 0,
            'child' => $arguments['child'] ?? 0,
            'selectedRoom' => $arguments['selectedRoom'] ?? '',
            'rooms' => $availableRooms,
        ]);

        return $this->htmlResponse();
    }


    public function reservationsubmitAction(): \Psr\Http\Message\ResponseInterface
    {
        $arguments = $this->request->getArguments();

        $isFormSubmitted = $this->request->getMethod() === 'POST'
            && !empty($arguments['name'])
            && !empty($arguments['email']);

        if (!$isFormSubmitted) {
            $rooms = $this->roomRepository->findAll();

            $this->view->assignMultiple([
                'checkin' => $arguments['checkin'] ?? '',
                'checkout' => $arguments['checkout'] ?? '',
                'adult' => $arguments['adult'] ?? 0,
                'child' => $arguments['child'] ?? 0,
                'selectedRoom' => $arguments['selectedRoom'] ?? '',
                'rooms' => $rooms,
            ]);

            return $this->htmlResponse();
        }

        $checkinTimestamp = strtotime($arguments['checkin'] ?? '');
        $checkoutTimestamp = strtotime($arguments['checkout'] ?? '');

        $bookingObj = new Booking();
        $bookingObj->setName($arguments['name'] ?? '');
        $bookingObj->setEmail($arguments['email'] ?? '');
        $bookingObj->setCheckin($checkinTimestamp);
        $bookingObj->setCheckout($checkoutTimestamp);
        $bookingObj->setAdults((int) ($arguments['adult'] ?? 0));
        $bookingObj->setChildren((int) ($arguments['child'] ?? 0));
        $bookingObj->setMessage($arguments['message'] ?? '');

        $roomUid = (int) ($arguments['room'] ?? $arguments['selectedRoom'] ?? 0);
        $room = $this->roomRepository->findByUid($roomUid);

        if ($room !== null) {
            $bookingObj->setRoom($room);
        }

        $site = $this->request->getAttribute('site');
        $settings = $site->getSettings();
        $bookingPid = (int) ($settings->get('bookingPid') ?? 0);

        if ($bookingPid > 0) {
            $bookingObj->_setProperty('pid', $bookingPid);
        }

        $this->bookingRepository->add($bookingObj);
        $this->persistenceManager->persistAll();

        $this->addFlashMessage('Booking saved successfully');

        return $this->htmlResponse();
    }
}