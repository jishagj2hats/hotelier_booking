<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:hotelier_booking/Resources/Private/Language/locallang_db.xlf:tx_hotelierbooking_domain_model_offer',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,slug,short_description,full_description',
        'iconfile' => 'EXT:hotelier_booking/Resources/Public/Icons/booking.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, slug, offer_type, image, apply_globally, rooms,
                --div--;Discount,
                    discount_type, discount_value,
                --div--;Validity,
                    valid_from, valid_until, usage_limit, usage_count,
                --div--;Description,
                    short_description, full_description,
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
                        'invertStateDisplay' => true,
                    ],
                ],
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
        'slug' => [
            'exclude' => false,
            'label' => 'Slug',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => '',
            ],
        ],
        'short_description' => [
            'exclude' => false,
            'label' => 'Short Description',
            'config' => [
                'type' => 'text',
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'full_description' => [
            'exclude' => false,
            'label' => 'Full Description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 10,
                'eval' => 'trim',
            ],
        ],
        'discount_type' => [
            'exclude' => false,
            'label' => 'Discount Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Percentage', 'value' => 'percentage'],
                    ['label' => 'Fixed', 'value' => 'fixed'],
                ],
                'default' => 'percentage',
            ],
        ],
        'discount_value' => [
            'exclude' => false,
            'label' => 'Discount Value',
            'config' => [
                'type' => 'number',
                'format' => 'decimal',
                'size' => 10,
                'default' => 0,
                'required' => true,
            ],
        ],
        'offer_type' => [
            'exclude' => false,
            'label' => 'Offer Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'General', 'value' => 'general'],
                    ['label' => 'Flash Deal', 'value' => 'flash'],
                    ['label' => 'Weekend Flash', 'value' => 'weekend'],
                    ['label' => 'Seasonal', 'value' => 'seasonal'],
                    ['label' => 'Member Only', 'value' => 'member'],
                ],
                'default' => 'general',
            ],
        ],
        'valid_from' => [
            'exclude' => false,
            'label' => 'Valid From',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'nullable' => true,
            ],
        ],
        'valid_until' => [
            'exclude' => false,
            'label' => 'Valid Until',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'nullable' => true,
            ],
        ],
        'apply_globally' => [
            'exclude' => false,
            'label' => 'Apply Globally (all rooms)',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'invertStateDisplay' => false],
                ],
                'default' => 0,
            ],
        ],
        'usage_limit' => [
            'exclude' => false,
            'label' => 'Usage Limit (0 = unlimited)',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'default' => 0,
            ],
        ],
        'usage_count' => [
            'exclude' => true,
            'label' => 'Usage Count',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'default' => 0,
                'readOnly' => true,
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
        'rooms' => [
            'exclude' => true,
            'label' => 'Rooms',
            'displayCond' => 'FIELD:apply_globally:!=:1',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_hotelierbooking_domain_model_room',
                'foreign_table_where' => 'AND tx_hotelierbooking_domain_model_room.hidden=0 ORDER BY tx_hotelierbooking_domain_model_room.title',
                'MM' => 'tx_hotelierbooking_offer_room_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'minitems' => 0,
                'maxitems' => 9999,
            ],
        ],
    ],
];

