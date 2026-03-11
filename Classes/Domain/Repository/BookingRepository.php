<?php
namespace Hotelier\HotelierBooking\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class BookingRepository extends Repository
{
    public function findBookedRangesByRoom(int $roomUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_booking');

        $rows = $queryBuilder
            ->select('checkin', 'checkout')
            ->from('tx_hotelierbooking_domain_model_booking')
            ->where(
                $queryBuilder->expr()->eq(
                    'room',
                    $queryBuilder->createNamedParameter($roomUid, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        return $rows ?: [];
    }

    // ── Count bookings in last 24 hours ───────────────────
    public function countBookingsLast24Hours(int $roomUid): int
    {
        $since = time() - 86400;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_booking');

        return (int) $queryBuilder
            ->count('uid')
            ->from('tx_hotelierbooking_domain_model_booking')
            ->where(
                $queryBuilder->expr()->eq(
                    'room',
                    $queryBuilder->createNamedParameter($roomUid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->gte(
                    'crdate',
                    $queryBuilder->createNamedParameter($since, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchOne();
    }

    // ── Get timestamp of last booking ─────────────────────
    public function getLastBookingTime(int $roomUid): ?int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_hotelierbooking_domain_model_booking');

        $result = $queryBuilder
            ->select('crdate')
            ->from('tx_hotelierbooking_domain_model_booking')
            ->where(
                $queryBuilder->expr()->eq(
                    'room',
                    $queryBuilder->createNamedParameter($roomUid, Connection::PARAM_INT)
                )
            )
            ->orderBy('crdate', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        return $result ? (int) $result : null;
    }
}