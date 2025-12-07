<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Hotelier Booking',
    'description' => 'Hotel room booking management system with categories, rooms, and filtering',
    'category' => 'plugin',
    'author' => 'Jisha G J',
    'author_email' => 'jisha@2hatslogic.com',
    'state' => 'stable',
    'version' => '1.0.0',
    'icon' => 'EXT:hotelier_booking/Resources/Public/Icons/booking.svg',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];