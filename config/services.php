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
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key' => '',
        'secret' => '',
    ],

    /**
     * Social OAuth
     */
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID', 'your-app-id'),
        'client_secret' => env('GITHUB_CLIENT_SECRET', 'your-app-secret'),
        'redirect' => env('GITHUB_REDIRECT', 'http://your-callback-url'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', 'your-app-id'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'your-app-secret'),
        'redirect' => env('GOOGLE_REDIRECT', 'http://your-callback-url'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', 'your-app-id'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'your-app-secret'),
        'redirect' => env('FACEBOOK_REDIRECT', 'http://your-callback-url'),
    ],

    'twitter' => [
        'consumer_key' => env('TWITTER_CONSUMER_KEY', 'consumer_key'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET', 'consumer_secret'),
        'token' => env('TWITTER_TOKEN', 'token'),
        'token_secret' => env('TWITTER_TOKEN_SECRET', 'token_secret'),
    ],

    'openExchangeRates' => [
        'appId' => env('OPEN_EXCHANGE_RATES_APP_ID', 'app_id'),
    ],

];
