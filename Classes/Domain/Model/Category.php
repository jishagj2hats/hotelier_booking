<?php
declare(strict_types=1);

namespace Hotelier\HotelierBooking\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Category extends AbstractEntity
{
    /**
     * @var bool
     */
    protected bool $hidden = false;

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * Hidden getter
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Hidden setter
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * Title getter
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Title setter
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Description getter
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Description setter
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}