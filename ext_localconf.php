<?php
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Hotelier\HotelierBooking\Controller\RoomController;
use Hotelier\HotelierBooking\Controller\BookingController;

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
    ExtensionUtility::configurePlugin(
        'HotelierBooking',
        'Booking',
        [
            BookingController::class => 'form, submit'
        ],
        [
            BookingController::class => 'submit'
        ]
    );
    ExtensionUtility::configurePlugin(
        'HotelierBooking',
        'ReservationForm',
        [
            BookingController::class => 'reservation, reservationsubmit'
        ],
        [
            BookingController::class => 'reservationsubmit'
        ]
    );


})();
