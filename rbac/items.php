<?php
return [
    'adminPanel' => [
        'type' => 2,
        'description' => 'Backend panel',
    ],
    'user' => [
        'type' => 1,
        'description' => 'User',
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Admin',
        'children' => [
            'user',
            'adminPanel',
        ],
    ],
];
