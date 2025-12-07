<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

/**
 * Icon registry configuration for hotelier_booking extension
 */
return [
    // Plugin icon
    'hotelier-booking-plugin' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hotelier_booking/Resources/Public/Icons/booking.svg',
    ],
    // Extension icon
    'extension-hotelier-booking' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hotelier_booking/Resources/Public/Icons/Extension.svg',
    ],
    // Category icon
    'hotelier-booking-category' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hotelier_booking/Resources/Public/Icons/Extension.svg',
    ],
    // Room icon
    'hotelier-booking-room' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:hotelier_booking/Resources/Public/Icons/room.svg',
    ],
];