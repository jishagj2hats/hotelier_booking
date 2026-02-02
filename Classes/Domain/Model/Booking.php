<?php
namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use Hotelier\HotelierBooking\Domain\Model\Room;

class Booking extends AbstractEntity
{
    protected string $name = '';
    protected string $email = '';
     protected int $checkin = 0;
    protected int $checkout = 0;
    protected int $adults = 0;
    protected int $children = 0;
    protected int $roomSelect = 0;
    protected string $message = '';
    protected ?Room $room = null;

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

        public function getCheckin(): int
    {
        return $this->checkin;
    }

    public function setCheckin(int $checkin): void
    {
        $this->checkin = $checkin;
    }

    public function getCheckout(): int
    {
        return $this->checkout;
    }

    public function setCheckout(int $checkout): void
    {
        $this->checkout = $checkout;
    }

    public function getAdults(): int { return $this->adults; }
    public function setAdults(int $adults): void { $this->adults = $adults; }

    public function getChildren(): int { return $this->children; }
    public function setChildren(int $children): void { $this->children = $children; }

    public function getRoomSelect(): int { return $this->roomSelect; }
    public function setRoomSelect(int $roomSelect): void { $this->roomSelect = $roomSelect; }

    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): void { $this->message = $message; }
}
