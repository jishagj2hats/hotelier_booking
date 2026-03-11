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
    public function findBestOfferForRoom(Room $room, ?int $now = null): ?Offer
    {
        $t = $now ?? time();
        $roomOffers = [];
        foreach ($room->getOffers() as $offer) {
            if ($offer instanceof Offer && $offer->isActive($t)) {
                $roomOffers[] = $offer;
            }
        }

        if (!empty($roomOffers)) {
            return $this->pickBestByDiscountAmount($roomOffers, $room->getRent());
        }

        $globalOffers = [];
        foreach ($this->offerRepository->findGlobalOffers($t) as $offer) {
            if ($offer instanceof Offer && $offer->isActive($t)) {
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

