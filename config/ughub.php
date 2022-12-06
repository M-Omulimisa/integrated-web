<?php

return [

    'environment' => env('UGHUB_ENV', ''),
    'test_url' => env('UGHUB_URL', 'https://'),
    'url' => env('UGHUB_URL', 'https://'),

    'nira' => [
        'test_key' => env('UGHUB_NIRA_KEY', ''),
        'test_secret' => env('UGHUB_NIRA_SECRET', ''),
        'test_username' => env('UGHUB_NIRA_USERNAME', ''),
        'test_password' => env('UGHUB_NIRA_PASSWORD', ''),
        'test_url' => env('UGHUB_NIRA_URL', 'https://'),

        'key' => env('UGHUB_NIRA_KEY', ''),
        'secret' => env('UGHUB_NIRA_SECRET', ''),
        'username' => env('UGHUB_NIRA_USERNAME', ''),
        'password' => env('UGHUB_NIRA_PASSWORD', ''),
        'url' => env('UGHUB_NIRA_URL', 'https://')
    ],

    'ursb' => [
        'test_key' => env('UGHUB_NIRA_KEY', ''),
        'test_secret' => env('UGHUB_NIRA_SECRET', ''),
        'test_url' => env('UGHUB_NIRA_URL', 'https://'),

        'key' => env('UGHUB_NIRA_KEY', ''),
        'secret' => env('UGHUB_NIRA_SECRET', ''),
        'url' => env('UGHUB_NIRA_URL', 'https://')         
    ]
];