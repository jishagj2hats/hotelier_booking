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


        if ($room) {
            $selectedRoom = $this->roomRepository->findByUid($room);

            $ranges = $this->bookingRepository->findBookedRangesByRoom($room);

            foreach ($ranges as $range) {
                $start = (int) $range['checkin'];
                $end = (int) $range['checkout'];

                for ($date = $start; $date <= $end; $date += 86400) {
                    $disabledDates[] = date('Y-m-d', $date);
                }
            }
        }
        $this->view->assignMultiple([
            'selectedRoom' => $selectedRoom,
            'roomUid' => $room,
            'disabledDates' => array_unique($disabledDates)
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
            $bookingObj->setRoom($room);
        }
        $site = $this->request->getAttribute('site');

        /** @var \TYPO3\CMS\Core\Site\Entity\SiteSettings $settings */
        $settings = $site->getSettings();

        $bookingPid = (int) $settings->get('bookingPid');

        if ($bookingPid > 0) {
            $bookingObj->_setProperty('pid', $bookingPid);
        }
        $this->bookingRepository->add($bookingObj);
        $this->persistenceManager->persistAll();

        $this->addFlashMessage('Booking saved successfully');
        return $this->redirect('form');
    }
    public function reservationAction(): \Psr\Http\Message\ResponseInterface
    {


        $arguments = $this->request->getArguments();
        $rooms = $this->roomRepository->findAll()->toArray();
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
    public function reservationsubmitAction(): \Psr\Http\Message\ResponseInterface
    {
        $arguments = $this->request->getArguments();

        // Check if this is a form submission (POST with name and email)
        // NOT just a page load from the previous form
        $isFormSubmitted = $this->request->getMethod() === 'POST'
            && !empty($arguments['name'])
            && !empty($arguments['email']);

        if (!$isFormSubmitted) {
            // This is the initial page load - just show the form with pre-filled data
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

        // ONLY save when user actually submits the final form
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
