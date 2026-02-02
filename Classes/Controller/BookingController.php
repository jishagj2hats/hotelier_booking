<?php
namespace Hotelier\HotelierBooking\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Hotelier\HotelierBooking\Domain\Repository\RoomRepository;
use Hotelier\HotelierBooking\Domain\Repository\BookingRepository;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use Hotelier\HotelierBooking\Domain\Model\Booking;

class BookingController extends ActionController
{
    public function __construct(
        protected RoomRepository $roomRepository,
        protected BookingRepository $bookingRepository,
        protected PersistenceManagerInterface $persistenceManager
    ) {}

public function formAction(int $room = null): \Psr\Http\Message\ResponseInterface
{
    $selectedRoom = null;
    $disabledDates = [];

    if ($room) {
        $selectedRoom = $this->roomRepository->findByUid($room);

        $ranges = $this->bookingRepository->findBookedRangesByRoom($room);

        foreach ($ranges as $range) {
            $start = (int)$range['checkin'];
            $end   = (int)$range['checkout'];

            for ($date = $start; $date <= $end; $date += 86400) {
                $disabledDates[] = date('Y-m-d', $date);
            }
        }
    }

    $this->view->assignMultiple([
        'selectedRoom' => $selectedRoom,
        'roomUid' => $room,
        'disabledDates' => array_unique($disabledDates),
    ]);

    return $this->htmlResponse();
}




public function submitAction(array $booking): \Psr\Http\Message\ResponseInterface
{
    $booking['checkin'] = strtotime($booking['checkin']);
    $booking['checkout'] = strtotime($booking['checkout']);

    $bookingObj = new Booking();
    $bookingObj->setName($booking['name']);
    $bookingObj->setEmail($booking['email']);
    $bookingObj->setCheckin($booking['checkin']);
    $bookingObj->setCheckout($booking['checkout']);
    $bookingObj->setAdults((int)$booking['adults']);
    $bookingObj->setChildren((int)$booking['children']);
    $bookingObj->setMessage($booking['message']);
    $roomUid = (int)($booking['room'] ?? 0);
    $room = $this->roomRepository->findByUid($roomUid);

    if ($room !== null) {
        $bookingObj->setRoom($room);
    }
    $site = $this->request->getAttribute('site');

    /** @var \TYPO3\CMS\Core\Site\Entity\SiteSettings $settings */
    $settings = $site->getSettings();

    $bookingPid = (int)$settings->get('bookingPid');

    if ($bookingPid > 0) {
    $bookingObj->_setProperty('pid', $bookingPid);
}
    $this->bookingRepository->add($bookingObj);
    $this->persistenceManager->persistAll();

    $this->addFlashMessage('Booking saved successfully');
    return $this->redirect('form');
}

}
