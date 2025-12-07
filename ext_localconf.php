<?php
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Hotelier\HotelierBooking\Controller\RoomController;

(function () {
    ExtensionUtility::configurePlugin(
        'HotelierBooking',
        'Rooms',
        [
            RoomController::class => 'list, show, filter'
        ],
        [
            RoomController::class => 'filter'
        ]
    );
})();
