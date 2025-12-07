<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:hotelier_booking/Resources/Private/Language/locallang_db.xlf:tx_hotelierbooking_domain_model_category',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description',
        'iconfile' => 'EXT:hotelier_booking/Resources/Public/Icons/category.svg'
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, description,
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
            'label' => 'Category Title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'max' => 255
            ]
        ],
        'description' => [
            'exclude' => false,
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
    ],
];