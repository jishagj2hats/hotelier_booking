<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Hotelier\HotelierBooking\Domain\Model\Attraction;
use Hotelier\HotelierBooking\Domain\Model\Offer;

class Room extends AbstractEntity
{
    protected string $title = '';
    protected ?Category $category = null;
    protected float $rent = 0.0;
    protected int $numberOfBeds = 0;
    protected int $numberOfBathrooms = 0;
    protected bool $wifiAvailable = false;
    protected string $description = '';
    protected int $numberOfRooms = 1;
    protected int $roomSize = 0;
    protected int $maxOccupancy = 1;
    protected bool $acAvailable = false;
    protected int $rating = 5;
    protected int $maxAdults = 2;
    protected int $maxChildren = 2;
    protected float $offerPrice = 0.0;
    protected int $offerFrom = 0;
    protected int $offerUntil = 0;
    protected float $gstRate = 18.0;
    protected float $serviceChargeRate = 10.0;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $images;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Hotelier\HotelierBooking\Domain\Model\Attraction>
     */
    protected $attractions;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Hotelier\HotelierBooking\Domain\Model\Offer>
     */
    protected $offers;

    public function getTitle(): string
    {
        return $this->title;
    }
    public function __construct()
    {
        $this->images = new ObjectStorage();
        $this->attractions = new ObjectStorage();
        $this->offers = new ObjectStorage();
    }
    public function initializeObject(): void
    {
        $this->images = $this->images ?? new ObjectStorage();
        $this->attractions = $this->attractions ?? new ObjectStorage();
        $this->offers = $this->offers ?? new ObjectStorage();
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
    public function getNumberOfRooms(): int
    {
        return $this->numberOfRooms;
    }

    public function setNumberOfRooms(int $numberOfRooms): void
    {
        $this->numberOfRooms = $numberOfRooms;
    }
    public function getRoomSize(): int
    {
        return $this->roomSize;
    }
    public function setRoomSize(int $roomSize): void
    {
        $this->roomSize = $roomSize;
    }

    public function getMaxOccupancy(): int
    {
        return $this->maxOccupancy;
    }
    public function setMaxOccupancy(int $maxOccupancy): void
    {
        $this->maxOccupancy = $maxOccupancy;
    }

    public function getAcAvailable(): bool
    {
        return $this->acAvailable;
    }
    public function setAcAvailable(bool $acAvailable): void
    {
        $this->acAvailable = $acAvailable;
    }
    public function getRating(): int
    {
        return $this->rating;
    }
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
    public function getMaxAdults(): int
    {
        return $this->maxAdults;
    }

    public function setMaxAdults(int $maxAdults): void
    {
        $this->maxAdults = $maxAdults;
    }

    public function getMaxChildren(): int
    {
        return $this->maxChildren;
    }

    public function setMaxChildren(int $maxChildren): void
    {
        $this->maxChildren = $maxChildren;
    }
    public function getOfferPrice(): float
    {
        return $this->offerPrice;
    }

    public function setOfferPrice(float $offerPrice): void
    {
        $this->offerPrice = $offerPrice;
    }

    public function getOfferUntil(): int
    {
        return $this->offerUntil;
    }

    public function setOfferUntil(int $offerUntil): void
    {
        $this->offerUntil = $offerUntil;
    }

    public function getOfferFrom(): int
    {
        return $this->offerFrom;
    }
    public function setOfferFrom(int $offerFrom): void
    {
        $this->offerFrom = $offerFrom;
    }

    // ✅ Updated: active only between offerFrom and offerUntil
    public function getActiveOfferPriceForDate(?int $timestamp = null): ?float
    {
        if ($this->offerPrice <= 0) {
            return null;
        }
        $checkTime = $timestamp ?? time();
        if ($this->offerFrom > 0 && $checkTime < $this->offerFrom) {
            return null;
        }
        if ($this->offerUntil > 0 && $checkTime > $this->offerUntil) {
            return null;
        }
        return $this->offerPrice;
    }
    public function getDisplayOfferPrice(): ?float
    {
        foreach ($this->getOffers() as $offer) {
            // Only skip if usage cap is hit — still show future/upcoming offers
            if ($offer->getUsageLimit() > 0 && $offer->getUsageCount() >= $offer->getUsageLimit()) {
                continue;
            }

            if ($offer->getDiscountType() === 'percentage') {
                $discounted = $this->rent - ($this->rent * $offer->getDiscountValue() / 100);
            } else {
                $discounted = $this->rent - $offer->getDiscountValue();
            }

            return max(0.0, round($discounted, 2));
        }

        return null;
    }

    public function getActiveOfferPrice(): ?float
    {
        return $this->getActiveOfferPriceForDate(time());
    }


    public function getEffectivePrice(): float
    {
        return $this->getActiveOfferPrice() ?? $this->rent;
    }

    public function getGstRate(): float
    {
        return $this->gstRate;
    }

    public function setGstRate(float $gstRate): void
    {
        $this->gstRate = $gstRate;
    }

    public function getServiceChargeRate(): float
    {
        return $this->serviceChargeRate;
    }

    public function setServiceChargeRate(float $serviceChargeRate): void
    {
        $this->serviceChargeRate = $serviceChargeRate;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Hotelier\HotelierBooking\Domain\Model\Attraction>
     */
    public function getAttractions(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->attractions;
    }

    public function setAttractions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $attractions): void
    {
        $this->attractions = $attractions;
    }

    public function addAttraction(Attraction $attraction): void
    {
        $this->attractions->attach($attraction);
    }

    public function removeAttraction(Attraction $attraction): void
    {
        $this->attractions->detach($attraction);
    }

    public function getOffers(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        if ($this->offers === null) {
            $this->offers = new ObjectStorage();
        }
        return $this->offers;
    }

    public function setOffers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $offers): void
    {
        $this->offers = $offers;
    }

    public function addOffer(Offer $offer): void
    {
        $this->offers->attach($offer);
    }

    public function removeOffer(Offer $offer): void
    {
        $this->offers->detach($offer);
    }
}