<?php
declare(strict_types=1);

defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// Register plugin
(static function (): void {
    ExtensionUtility::registerPlugin(
        'HotelierBooking',          
        'Rooms',                    
        'Hotel Rooms Listing',      
        'hotelier-booking-room',    
        'Plugins',                  
        'Displays hotel room list'
    );
    // plugin signature
$pluginSignature = 'hotelierbooking_rooms';

ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:hotelier_booking/Configuration/FlexForms/flexform_booking.xml',
        $pluginSignature
    );
    // Add the FlexForm to the show item list
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin, pi_flexform',
        $pluginSignature,
        'after:palette:headers'
    );
})();


