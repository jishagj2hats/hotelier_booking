<?php
declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'LLL:EXT:hotelier_booking/Resources/Private/Language/locallang_db.xlf:tx_hotelierbooking_domain_model_attraction',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description,category,suggested_label',
        'iconfile' => 'EXT:hotelier_booking/Resources/Public/Icons/attraction.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    title, category, suggested_label,
                --div--;Details,
                    description, distance_km, travel_time, map_link,
                --div--;Media,
                    image,
                --div--;Access,
                    hidden
            ',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'room' => [
            'exclude' => true,
            'label' => 'Room',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_hotelierbooking_domain_model_room',
                'default' => 0,
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'category' => [
            'exclude' => false,
            'label' => 'Category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['-- please choose --', ''],
                    ['Beach', 'beach'],
                    ['Landmark', 'landmark'],
                    ['Shopping', 'shopping'],
                    ['Restaurant', 'restaurant'],
                    ['Entertainment', 'entertainment'],
                    ['Park & nature', 'park'],
                ],
                'default' => '',
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'distance_km' => [
            'exclude' => false,
            'label' => 'Distance (km)',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 8,
                'default' => 0,
            ],
        ],
        'travel_time' => [
            'exclude' => false,
            'label' => 'Travel time (e.g. \"5 min by car\")',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'Image',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'map_link' => [
            'exclude' => false,
            'label' => 'Map link (Google Maps URL)',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'eval' => 'trim',
            ],
        ],
        'suggested_label' => [
            'exclude' => false,
            'label' => 'Suggested label (e.g. \"5 minutes from beach\")',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
    ],
];

