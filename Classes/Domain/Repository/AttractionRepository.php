<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class AttractionRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}

