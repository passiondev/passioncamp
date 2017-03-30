<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'key' => env('MANDRILL_KEY'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        // 'pcc' => [
        //     'key'    => env('STRIPE_KEY_PCC'),
        //     'secret' => env('STRIPE_SECRET_PCC'),
        // ]
        'pcc' => [
            'key'    => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
        ]
    ],

    'echosign' => [
        'callback' => env('ECHOSIGN_CALLBACK', 'echosign.callback'),
    ],

    'rollbar' => [
        'access_token' => env('ROLLBAR_TOKEN'),
        'level' => env('ROLLBAR_LEVEL'),
    ],

    'printnode' => [
        'key' => env('PRINTNODE_API_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_KEY'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => '',
    ],
];
