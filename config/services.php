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

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'firebase' => [

        'api_key' => 'AIzaSyA8iMfEFwgM8h3WyCNGrBV7jK1w0D4xNh0',
        'auth_domain' => 'wbbpasswordmanager.firebaseapp.com',
        'database_url' => 'https://wbbpasswordmanager.firebaseio.com',
        'secret' => 'eiAp5Armx42BNDp02Aitwry9YcYhUM0cgED5dE5m',
        'storage_bucket' => 'wbbpasswordmanager.appspot.com',
    ]

];
