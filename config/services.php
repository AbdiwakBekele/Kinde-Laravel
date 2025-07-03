<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'kinde' => [
        'client_id' => env('KINDE_CLIENT_ID'),
        'client_secret' => env('KINDE_CLIENT_SECRET'),
        'redirect' => env('KINDE_REDIRECT_URI'),
        'base_uri' => env('KINDE_DOMAIN'),
        'api_token' => env('KINDE_API_TOKEN'),
        'logout_redirect' => env('KINDE_LOGOUT_REDIRECT'),
    ],

    'kinde_m2m' => [
        'client_id' => env('KINDE_M2M_CLIENT_ID'),
        'client_secret' => env('KINDE_M2M_CLIENT_SECRET'),
        'audience' => env('KINDE_M2M_API_AUDIENCE'),
        'base_uri' => env('KINDE_DOMAIN'),
    ],


];