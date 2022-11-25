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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '811518473718-9pq0re7qdl6b5n588nhota5fffvb0ktm.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://127.0.0.1:8000/google/auth',
    ],
    'facebook' => [
        'client_id' => '1186134298969152',
        'client_secret' => 'd81016643b8ab7f32a56ec39b9ea18f3',
        'redirect' => 'http://localhost:8000/facebook/auth',
    ]
];
