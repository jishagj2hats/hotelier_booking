<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Attraction extends AbstractEntity
{
    protected string $title = '';
    protected string $category = '';
    protected string $description = '';
    protected float $distanceKm = 0.0;
    protected string $travelTime = '';
    protected ?FileReference $image = null;
    protected string $mapLink = '';
    protected string $suggestedLabel = '';
    protected ?Room $room = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDistanceKm(): float
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(float $distanceKm): void
    {
        $this->distanceKm = $distanceKm;
    }

    public function getTravelTime(): string
    {
        return $this->travelTime;
    }

    public function setTravelTime(string $travelTime): void
    {
        $this->travelTime = $travelTime;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function setImage(?FileReference $image): void
    {
        $this->image = $image;
    }

    public function getMapLink(): string
    {
        return $this->mapLink;
    }

    public function setMapLink(string $mapLink): void
    {
        $this->mapLink = $mapLink;
    }

    public function getSuggestedLabel(): string
    {
        return $this->suggestedLabel;
    }

    public function setSuggestedLabel(string $suggestedLabel): void
    {
        $this->suggestedLabel = $suggestedLabel;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): void
    {
        $this->room = $room;
    }
}

