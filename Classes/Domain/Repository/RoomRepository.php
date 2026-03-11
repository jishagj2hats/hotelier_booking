<?php
declare(strict_types=1);
namespace Hotelier\HotelierBooking\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class RoomRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function filterRooms(
        ?string $checkin,
        ?string $checkout,
        int $adults = 0,
        int $children = 0
    ): array {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_room');

        $queryBuilder
            ->select('r.*')
            ->from('tx_hotelierbooking_domain_model_room', 'r')
            ->where(
                $queryBuilder->expr()->eq('r.deleted', 0),
                $queryBuilder->expr()->eq('r.hidden', 0)
            );

        // ── Adults filter ────────────────────────────────────
        if ($adults > 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->gte(
                    'r.max_adults',
                    $queryBuilder->createNamedParameter($adults, Connection::PARAM_INT)
                )
            );
        }

        // ── Children filter ──────────────────────────────────
        if ($children > 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->gte(
                    'r.max_children',
                    $queryBuilder->createNamedParameter($children, Connection::PARAM_INT)
                )
            );
        }

        // ── Total occupancy filter ───────────────────────────
        $totalGuests = $adults + $children;
        if ($totalGuests > 0) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->gte(
                    'r.max_occupancy',
                    $queryBuilder->createNamedParameter($totalGuests, Connection::PARAM_INT)
                )
            );
        }

        // ── Date availability filter ─────────────────────────
        if (!empty($checkin) && !empty($checkout)) {
            $checkinTs = strtotime($checkin);
            $checkoutTs = strtotime($checkout);

            // Count overlapping bookings per room
            $bookedQb = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_booking');

            $bookedCounts = $bookedQb
                ->select('b.room')
                ->addSelectLiteral('COUNT(b.uid) AS booking_count')
                ->from('tx_hotelierbooking_domain_model_booking', 'b')
                ->where(
                    $bookedQb->expr()->eq('b.deleted', 0),
                    $bookedQb->expr()->lt(
                        'b.checkin',
                        $bookedQb->createNamedParameter($checkoutTs, Connection::PARAM_INT)
                    ),
                    $bookedQb->expr()->gt(
                        'b.checkout',
                        $bookedQb->createNamedParameter($checkinTs, Connection::PARAM_INT)
                    )
                )
                ->groupBy('b.room')
                ->executeQuery()
                ->fetchAllAssociative();

            // Only exclude rooms where booking_count >= number_of_rooms
            $fullyBookedUids = [];

            foreach ($bookedCounts as $row) {
                $roomQb = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_room');

                $roomData = $roomQb
                    ->select('number_of_rooms')
                    ->from('tx_hotelierbooking_domain_model_room')
                    ->where(
                        $roomQb->expr()->eq(
                            'uid',
                            $roomQb->createNamedParameter((int) $row['room'], Connection::PARAM_INT)
                        )
                    )
                    ->executeQuery()
                    ->fetchAssociative();

                if ($roomData && (int) $row['booking_count'] >= (int) $roomData['number_of_rooms']) {
                    $fullyBookedUids[] = (int) $row['room'];
                }
            }

            if (!empty($fullyBookedUids)) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->notIn('r.uid', $fullyBookedUids)
                );
            }
        }

        $queryBuilder->orderBy('r.rent', 'ASC');

        $rows = $queryBuilder->executeQuery()->fetchAllAssociative();

        if (empty($rows)) {
            return [];
        }

        $uids = array_column($rows, 'uid');
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->matching($query->in('uid', $uids));

        return $query->execute()->toArray();
    }
}