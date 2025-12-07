<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:hotelier_booking/Resources/Private/Language/locallang_db.xlf:tx_hotelierbooking_domain_model_room',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:hotelier_booking/Resources/Public/Icons/room.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, category, rent,
                --div--;Details,
                    number_of_beds, number_of_bathrooms, wifi_available,
                --div--;Media,
                    images,
                --div--;Description,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
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
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],
        'title' => [
            'exclude' => false,
            'label' => 'Room Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ]
        ],
        'category' => [
            'exclude' => false,
            'label' => 'Category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_hotelierbooking_domain_model_category',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
        'images' => [
            'exclude' => true,
            'label' => 'Room Images',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 15, 
            ],
        ],
        'rent' => [
            'exclude' => false,
            'label' => 'Rent per Night',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'default' => 0,
                'required' => true
            ]
        ],
        'number_of_beds' => [
            'exclude' => false,
            'label' => 'Number of Beds',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 0,
                'required' => true
            ]
        ],
        'number_of_bathrooms' => [
            'exclude' => false,
            'label' => 'Number of Bathrooms',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 0,
                'required' => true
            ]
        ],
        'wifi_available' => [
            'exclude' => false,
            'label' => 'WiFi Available',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => false
                    ]
                ]
            ]
        ],
        'description' => [
            'exclude' => false,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
    ],
];