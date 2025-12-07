<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class RoomRepository extends Repository
{
     public function initializeObject()
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false); // IMPORTANT
        $this->setDefaultQuerySettings($querySettings);
    }
    public function filterRooms(array $filterData)
    {
        $query = $this->createQuery();

        // Example filter
        if (!empty($filterData['category'])) {
            $query->matching(
                $query->equals('category', (int)$filterData['category'])
            );
        }

        return $query->execute();
    }
}
