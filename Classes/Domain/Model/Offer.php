<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Offer extends AbstractEntity
{
    protected string $title = '';
    protected string $slug = '';
    protected string $shortDescription = '';
    protected string $fullDescription = '';
    protected string $discountType = 'percentage'; // percentage|fixed
    protected float $discountValue = 0.0;
    protected string $offerType = 'general'; // general|flash|weekend|seasonal|member
    protected int $validFrom = 0;
    protected int $validUntil = 0;
    protected bool $applyGlobally = false;
    protected int $usageLimit = 0;
    protected int $usageCount = 0;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $image;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Hotelier\HotelierBooking\Domain\Model\Room>
     */
    protected $rooms;

    public function __construct()
    {
        $this->image = new ObjectStorage();
        $this->rooms = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getFullDescription(): string
    {
        return $this->fullDescription;
    }

    public function setFullDescription(string $fullDescription): void
    {
        $this->fullDescription = $fullDescription;
    }

    public function getDiscountType(): string
    {
        return $this->discountType;
    }

    public function setDiscountType(string $discountType): void
    {
        $this->discountType = $discountType;
    }

    public function getDiscountValue(): float
    {
        return $this->discountValue;
    }

    public function setDiscountValue(float $discountValue): void
    {
        $this->discountValue = $discountValue;
    }

    public function getOfferType(): string
    {
        return $this->offerType;
    }

    public function setOfferType(string $offerType): void
    {
        $this->offerType = $offerType;
    }

    public function getValidFrom(): int
    {
        return $this->validFrom;
    }

    public function setValidFrom(int $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getValidUntil(): int
    {
        return $this->validUntil;
    }

    public function setValidUntil(int $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function isApplyGlobally(): bool
    {
        return $this->applyGlobally;
    }

    public function getApplyGlobally(): bool
    {
        return $this->applyGlobally;
    }

    public function setApplyGlobally(bool $applyGlobally): void
    {
        $this->applyGlobally = $applyGlobally;
    }

    public function getUsageLimit(): int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(int $usageLimit): void
    {
        $this->usageLimit = $usageLimit;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function setUsageCount(int $usageCount): void
    {
        $this->usageCount = $usageCount;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function addImage(FileReference $image): void
    {
        $this->image->attach($image);
    }

    public function removeImage(FileReference $image): void
    {
        $this->image->detach($image);
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Hotelier\HotelierBooking\Domain\Model\Room>
     */
    public function getRooms(): ObjectStorage
    {
        return $this->rooms;
    }

    public function setRooms(ObjectStorage $rooms): void
    {
        $this->rooms = $rooms;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms->attach($room);
    }

    public function removeRoom(Room $room): void
    {
        $this->rooms->detach($room);
    }

    public function isExpired(?int $now = null): bool
    {
        $t = $now ?? time();
        return $this->validUntil > 0 && $t > $this->validUntil;
    }

    public function isActive(?int $now = null): bool
    {
        $t = $now ?? time();
        if ($this->validFrom > 0 && $t < $this->validFrom) {
            return false;
        }
        if ($this->validUntil > 0 && $t > $this->validUntil) {
            return false;
        }
        if ($this->usageLimit > 0 && $this->usageCount >= $this->usageLimit) {
            return false;
        }
        return true;
    }

    public function getSecondsUntilEnd(?int $now = null): ?int
    {
        if ($this->validUntil <= 0) {
            return null;
        }
        $t = $now ?? time();
        return max(0, $this->validUntil - $t);
    }
}

