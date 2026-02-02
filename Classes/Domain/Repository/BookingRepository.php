<?php
namespace Hotelier\HotelierBooking\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class BookingRepository extends Repository
{
    public function findBookedRangesByRoom(int $roomUid): array
{
    $queryBuilder = $this->createQuery()->getQueryBuilder();

    $rows = $queryBuilder
        ->select('checkin', 'checkout')
        ->from('tx_hotelierbooking_domain_model_booking')
        ->where(
            $queryBuilder->expr()->eq(
                'room',
                $queryBuilder->createNamedParameter($roomUid, \PDO::PARAM_INT)
            )
        )
        ->executeQuery()
        ->fetchAllAssociative();

    return $rows ?: [];
}

}
