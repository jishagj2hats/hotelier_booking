<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Hotelier\HotelierBooking\Domain\Repository\RoomRepository;

class RoomController extends ActionController
{
    /**
     * @var RoomRepository
     */
    protected RoomRepository $roomRepository;

    /**
     * Inject repository (TYPO3 v10–v14)
     */
    public function injectRoomRepository(RoomRepository $roomRepository): void
    {
        $this->roomRepository = $roomRepository;
    }

   /**
     * List rooms
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $rooms = $this->roomRepository->findAll();

        $this->view->assignMultiple([
            'rooms' => $rooms,
        ]);

        return $this->htmlResponse();
    }


    /**
     * Show single room
     */
    public function showAction(\Hotelier\HotelierBooking\Domain\Model\Room $room): void
    {
        $this->view->assign('room', $room);
    }

    /**
     * Filter rooms (example)
     */
    public function filterAction(): void
    {
        $filterData = $this->request->getArguments();
        $rooms = $this->roomRepository->filterRooms($filterData);

        $this->view->assign('rooms', $rooms);
    }
}
