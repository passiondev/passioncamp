<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'pcc' => [
            'key' => env('STRIPE_KEY_PCC'),
            'secret' => env('STRIPE_SECRET_PCC'),
        ],
    ],

    'adobesign' => [
        'key' => env('ADOBESIGN_KEY'),
        'secret' => env('ADOBESIGN_SECRET'),
        'refresh' => env('ADOBESIGN_REFRESH'),
    ],

    'rollbar' => [
        'access_token' => env('ROLLBAR_TOKEN'),
        'level' => env('ROLLBAR_LEVEL'),
    ],

    'printnode' => [
        'key' => env('PRINTNODE_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_KEY'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect' => 'https://portal.passioncamp.268generation.com/oauth/google/callback',
    ],

];
