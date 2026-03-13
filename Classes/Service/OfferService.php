<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Service;

use Hotelier\HotelierBooking\Domain\Model\Offer;
use Hotelier\HotelierBooking\Domain\Model\Room;
use Hotelier\HotelierBooking\Domain\Repository\OfferRepository;

class OfferService
{
    public function __construct(
        private readonly OfferRepository $offerRepository
    ) {
    }

    /**
     * Returns best active offer for the room (room-specific beats global, best discount wins, no stacking).
     */
    // Add a $forDisplay parameter so the offer page skips the date check

    public function findBestOfferForRoom(Room $room, ?int $now = null, bool $forDisplay = false): ?Offer
    {
        $t = $now ?? time();
        $roomOffers = [];

        foreach ($room->getOffers() as $offer) {
            // For display: only skip usage-capped offers, allow future/past dates
            // For booking: require fully active (date + usage checks)
            $valid = $forDisplay
                ? !($offer->getUsageLimit() > 0 && $offer->getUsageCount() >= $offer->getUsageLimit())
                : $offer->isActive($t);

            if ($valid) {
                $roomOffers[] = $offer;
            }
        }

        if (!empty($roomOffers)) {
            return $this->pickBestByDiscountAmount($roomOffers, $room->getRent());
        }

        // Global offers — same logic
        $globalOffers = [];
        foreach ($this->offerRepository->findGlobalOffers($t) as $offer) {
            $valid = $forDisplay
                ? !($offer->getUsageLimit() > 0 && $offer->getUsageCount() >= $offer->getUsageLimit())
                : $offer->isActive($t);

            if ($valid) {
                $globalOffers[] = $offer;
            }
        }

        if (!empty($globalOffers)) {
            return $this->pickBestByDiscountAmount($globalOffers, $room->getRent());
        }

        return null;
    }

    public function calculateDiscountedPrice(Room $room, ?Offer $offer): ?float
    {
        if ($offer === null) {
            return null;
        }
        $rent = max(0.0, $room->getRent());
        $discount = $this->calculateDiscountAmount($rent, $offer);
        return max(0.0, $rent - $discount);
    }

    /**
     * @param Offer[] $offers
     */
    private function pickBestByDiscountAmount(array $offers, float $rent): ?Offer
    {
        $best = null;
        $bestAmount = -1.0;
        foreach ($offers as $offer) {
            $amount = $this->calculateDiscountAmount($rent, $offer);
            if ($amount > $bestAmount) {
                $bestAmount = $amount;
                $best = $offer;
            }
        }
        return $best;
    }

    private function calculateDiscountAmount(float $rent, Offer $offer): float
    {
        $value = max(0.0, $offer->getDiscountValue());
        if ($offer->getDiscountType() === 'percentage') {
            return $rent * (min(100.0, $value) / 100.0);
        }
        return min($rent, $value);
    }
}

