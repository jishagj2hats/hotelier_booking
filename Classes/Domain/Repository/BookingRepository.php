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
}