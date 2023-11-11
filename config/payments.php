<?php
return [
    'payment_default_service' => env("PAYMENT_SERVICE", "yo_ug"),
    'services' => [
        'yo_ug' => [
            "phoneapi" => env('YOUG_PHONE', null),
            "payapi" => env('YOUG_PAY', null),
            "class" => \App\Services\Payments\YoUganda::class,
            
            "url" => env('YOUG_URL', null),
            "username" => env('YOUG_USERNAME', null),
            "password" => env('YOUG_PASSWORD', null),
            "test" => [
                "url" => env('YOUG_TEST_URL', null),
                "username" => env('YOUG_TEST_USERNAME', null),
                "password" => env('YOUG_TEST_PASSWORD', null),
            ]
        ],
        'mtn_debit_ug' => [
            'class' => \App\Services\Payments\MtnUganda::class,
            'username' => env("MTNUG_DEBIT_USERNAME", null),
            'password' => env('MTNUG_DEBIT_PASSWORD', null),
            'certificate' => '',
            'key' => ''
        ],
        'mtn_sptransfer_ug' => [
            'class' => \App\Services\Payments\MtnUganda::class,
            'username' => env("MTNUG_SPTRANSFER_USERNAME", null),
            'password' => env('MTNUG_SPTRANSFER_PASSWORD', null),
            'certificate' => '',
            'key' => ''
        ]
    ],
];
