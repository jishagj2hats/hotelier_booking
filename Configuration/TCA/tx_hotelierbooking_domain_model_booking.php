<?php
return [
    'ctrl' => [
        'title' => 'Bookings',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'iconfile' => 'EXT:hotelier_booking/Resources/Public/Icons/booking.svg',
    ],
    'columns' => [
        'name' => [
            'label' => 'Name',
            'config' => ['type' => 'input', 'eval' => 'trim,required'],
        ],
        'email' => [
            'label' => 'Email',
            'config' => ['type' => 'input', 'eval' => 'trim,required'],
        ],
        'checkin' => [
    'label' => 'Check-in Date & Time',
    'config' => [
        'type' => 'input',
        'renderType' => 'inputDateTime',
        'eval' => 'datetime',
        'default' => 0,
    ],
],

'checkout' => [
    'label' => 'Check-out Date & Time',
    'config' => [
        'type' => 'input',
        'renderType' => 'inputDateTime',
        'eval' => 'datetime',
        'default' => 0,
    ],
],

        'adults' => [
            'label' => 'Adults',
            'config' => ['type' => 'input', 'eval' => 'int'],
        ],
        'children' => [
            'label' => 'Children',
            'config' => ['type' => 'input', 'eval' => 'int'],
        ],
        'room_select' => [
            'label' => 'Selected Room',
            'config' => ['type' => 'input'],
        ],
        'message' => [
            'label' => 'Message',
            'config' => ['type' => 'text'],
        ],
        'room' => [
    'label' => 'Room',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'foreign_table' => 'tx_hotelierbooking_domain_model_room',
        'foreign_table_where' => 'AND tx_hotelierbooking_domain_model_room.hidden=0 ORDER BY tx_hotelierbooking_domain_model_room.title',
        'items' => [
            ['label' => 'Please select a room', 'value' => 0]
        ],
        'default' => 0,
        'minitems' => 1,
        'maxitems' => 1,
        'required' => true
    ],
],


    ],
    'types' => [
        '1' => [
            'showitem' =>
                'name, email, checkin, checkout, adults, children, room_select, message, room'
        ],
    ],
];
