<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Room extends AbstractEntity
{
    protected string $title = '';
    protected ?Category $category = null;
    protected float $rent = 0.0;
    protected int $numberOfBeds = 0;
    protected int $numberOfBathrooms = 0;
    protected bool $wifiAvailable = false;
    protected string $description = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $images;

    public function getTitle(): string
    {
        return $this->title;
    }
    public function __construct()
    {
        $this->images = new ObjectStorage();
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Adds a Image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $images
     * @return void
     */
    public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $images): void
    {
        $this->images->attach($images);
    }

    /**
     * Removes a Image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove The images to be removed
     * @return void
     */
    public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove): void
    {
        $this->images->detach($imageToRemove);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImages(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->images;
    }

    public function setImages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images): void
    {
        $this->images = $images;
    }

    public function getRent(): float
    {
        return $this->rent;
    }

    public function setRent(float $rent): void
    {
        $this->rent = $rent;
    }

    public function getNumberOfBeds(): int
    {
        return $this->numberOfBeds;
    }

    public function setNumberOfBeds(int $numberOfBeds): void
    {
        $this->numberOfBeds = $numberOfBeds;
    }

    public function getNumberOfBathrooms(): int
    {
        return $this->numberOfBathrooms;
    }

    public function setNumberOfBathrooms(int $numberOfBathrooms): void
    {
        $this->numberOfBathrooms = $numberOfBathrooms;
    }

    public function isWifiAvailable(): bool
    {
        return $this->wifiAvailable;
    }

    public function getWifiAvailable(): bool
    {
        return $this->wifiAvailable;
    }

    public function setWifiAvailable(bool $wifiAvailable): void
    {
        $this->wifiAvailable = $wifiAvailable;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}