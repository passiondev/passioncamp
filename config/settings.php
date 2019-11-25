<?php

return [
    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'pcc' => [
            'key' => env('STRIPE_KEY_PCC'),
            'secret' => env('STRIPE_SECRET_PCC'),
        ],
    ],
];
