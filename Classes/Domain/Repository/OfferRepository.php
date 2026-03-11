<?php
declare(strict_types=1);
namespace Hotelier\HotelierBooking\Domain\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class OfferRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    // ── Offers landing page — show active + upcoming, hide expired ──
    public function findNonExpiredOffers(?int $now = null): QueryResultInterface
    {
        $t = $now ?? time();
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->equals('validUntil', 0),
                $query->greaterThanOrEqual('validUntil', $t)
            )
        );
        $query->setOrderings([
            'validFrom' => QueryInterface::ORDER_ASCENDING
        ]);
        return $query->execute();
    }

    // ── Room cards — only currently active offers ───────────────────
    public function findActiveOffers(?int $now = null): QueryResultInterface
    {
        $t = $now ?? time();
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->equals('validFrom', 0),
                    $query->lessThanOrEqual('validFrom', $t),
                ),
                $query->logicalOr(
                    $query->equals('validUntil', 0),
                    $query->greaterThanOrEqual('validUntil', $t),
                )
            )
        );
        return $query->execute();
    }

    // ── Filter by type — non-expired only ──────────────────────────
    public function findByType(string $offerType, ?int $now = null): QueryResultInterface
    {
        $t = $now ?? time();
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('offerType', $offerType),
                $query->logicalOr(
                    $query->equals('validUntil', 0),
                    $query->greaterThanOrEqual('validUntil', $t)
                )
            )
        );
        return $query->execute();
    }

    // ── Global offers — active only ────────────────────────────────
    public function findGlobalOffers(?int $now = null): QueryResultInterface
    {
        $t = $now ?? time();
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('applyGlobally', true),
                $query->logicalOr(
                    $query->equals('validFrom', 0),
                    $query->lessThanOrEqual('validFrom', $t),
                ),
                $query->logicalOr(
                    $query->equals('validUntil', 0),
                    $query->greaterThanOrEqual('validUntil', $t),
                )
            )
        );
        return $query->execute();
    }
}