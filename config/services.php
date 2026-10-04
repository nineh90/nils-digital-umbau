<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
     * Das Ticketsystem auf intern.nils-digital.de.
     *
     * url ist der Weg für die Schnittstelle – auf dem Server das Docker-Netz
     * (http://ticketsystem), ohne Umweg übers Internet. adresse ist das, was
     * ein Mensch im Browser öffnet. Bleiben url oder token leer, wird nichts
     * übergeben.
     */
    'ticketsystem' => [
        'url' => env('TICKETSYSTEM_URL'),
        'token' => env('TICKETSYSTEM_TOKEN'),
        'projekt' => env('TICKETSYSTEM_PROJEKT', 'anfragen'),
        'adresse' => env('TICKETSYSTEM_ADRESSE', 'https://intern.nils-digital.de'),
    ],

];
