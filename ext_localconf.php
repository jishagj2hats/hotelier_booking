<?php
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Hotelier\HotelierBooking\Controller\RoomController;
use Hotelier\HotelierBooking\Controller\BookingController;
use Hotelier\HotelierBooking\Controller\OfferController;

(function () {
    ExtensionUtility::configurePlugin(
        'HotelierBooking',
        'Rooms',
        [
            RoomController::class => 'list, show, filter, ajaxFilter, booking, offerRooms'
        ],
        [
            RoomController::class => 'filter,ajaxFilter'
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

    ExtensionUtility::configurePlugin(
        'HotelierBooking',
        'Offers',
        [
            OfferController::class => 'list'
        ],
        []
    );


})();
