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
                    title, category, rent, offer_price, offer_from, offer_until, number_of_rooms, gst_rate, service_charge_rate,
                --div--;Details,
                    room_size, max_occupancy, max_adults, max_children,
                    number_of_beds, number_of_bathrooms,
                    ac_available, wifi_available, rating,
                --div--;Offers,
                    offers,
                --div--;Media,
                    images,
                --div--;Description,
                    description,
                --div--;Local Attractions,
                    attractions,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
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
        // ── Offer Price ──────────────────────────────────────
        'offer_price' => [
            'exclude' => false,
            'label' => 'Offer Price (per Night)',
            'description' => 'Leave 0 for no offer. Only active if Offer Until date is set.',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'default' => 0,
            ]
        ],
        'offer_until' => [
            'exclude' => false,
            'label' => 'Offer Valid Until',
            'description' => 'Offer expires automatically after this date.',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'default' => 0,
                'nullable' => true,
            ]
        ],
        // ── Room Numbers ─────────────────────────────────────
        'number_of_rooms' => [
            'exclude' => false,
            'label' => 'Number of Rooms',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 1,
                'required' => true
            ]
        ],
        // ── Details ──────────────────────────────────────────
        'room_size' => [
            'exclude' => false,
            'label' => 'Room Size (sqft)',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 0,
            ]
        ],
        'max_occupancy' => [
            'exclude' => false,
            'label' => 'Max Occupancy (Total Guests)',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 1,
                'required' => true
            ]
        ],
        'max_adults' => [
            'label' => 'Max Adults',
            'description' => 'Must be less than Max Occupancy. Adults + Children should not exceed Max Occupancy.',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 2,
            ]
        ],
        'max_children' => [
            'label' => 'Max Children',
            'description' => 'Must be less than Max Occupancy. Adults + Children should not exceed Max Occupancy.',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 2,
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
        'ac_available' => [
            'exclude' => false,
            'label' => 'AC Available',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'invertStateDisplay' => false]
                ]
            ]
        ],
        'wifi_available' => [
            'exclude' => false,
            'label' => 'WiFi Available',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'invertStateDisplay' => false]
                ]
            ]
        ],
        'rating' => [
            'exclude' => false,
            'label' => 'Rating (1-5)',
            'config' => [
                'type' => 'number',
                'size' => 3,
                'default' => 5,
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
        'offer_from' => [
            'exclude' => false,
            'label' => 'Offer Valid From',
            'description' => 'Offer starts from this date.',
            'config' => [
                'type' => 'datetime',
                'format' => 'date',
                'default' => 0,
                'nullable' => true,
            ]
        ],
        'gst_rate' => [
            'exclude' => false,
            'label' => 'GST Rate (%)',
            'description' => 'Goods and Services Tax percentage for this room.',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 18,
                'eval' => 'trim',
            ],
        ],
        'service_charge_rate' => [
            'exclude' => false,
            'label' => 'Service Charge Rate (%)',
            'description' => 'Service charge percentage for this room.',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'default' => 10,
                'eval' => 'trim',
            ],
        ],
        'attractions' => [
            'exclude' => true,
            'label' => 'Local Attractions',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_hotelierbooking_domain_model_attraction',
                'foreign_field' => 'room',
                'appearance' => [
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'useSortable' => true,
                ],
            ],
        ],
        'offers' => [
            'exclude' => true,
            'label' => 'Offers',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_hotelierbooking_domain_model_offer',
                'foreign_table_where' => 'AND tx_hotelierbooking_domain_model_offer.hidden=0 ORDER BY tx_hotelierbooking_domain_model_offer.title',
                'MM' => 'tx_hotelierbooking_offer_room_mm',
                'MM_opposite_field' => 'rooms',
                'size' => 10,
                'autoSizeMax' => 30,
                'minitems' => 0,
                'maxitems' => 9999,
            ],
        ],
    ],
];