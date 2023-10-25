<?php
return [
    'otp_service_enabled' => true,
    'otp_default_service' => env("OTP_SERVICE", "dmark"),
    'services' => [
        'biotekno' => [
            "class" => \App\Services\OtpServices\BioTekno::class,
            "username" => env('OTP_USERNAME', null),
            "password" => env('OTP_PASSWORD', null),
            "transmission_id" => env('OTP_TRANSMISSION_ID', null)
        ],
        'nexmo' => [
            'class' => \App\Services\OtpServices\Nexmo::class,
            'api_key' => env("OTP_API_KEY", null),
            'api_secret' => env('OTP_API_SECRET', null),
            'from' => env('OTP_FROM', null)
        ],
        'twilio' => [
            'class' => \App\Services\OtpServices\Twilio::class,
            'account_sid' => env("OTP_ACCOUNT_SID", null),
            'auth_token' => env("OTP_AUTH_TOKEN", null),
            'from' => env("OTP_FROM", null)
        ],
        'ait' => [
            'class' => \App\Services\OtpServices\AfricaIsTalking::class,
            'username' => env("AIT_USERNAME", null),
            'api_key' => env("AIT_API_KEY", null),
            'from' => env("OTP_FROM", null)
        ],
        'dmark' => [
            'class' => \App\Services\OtpServices\Dmark::class,
            'username' => env("DMARK_USERNAME", null),
            'api_key' => env("DMARK_PASSWORD", null),
            'from' => env("OTP_FROM", null)
        ]
    ],
    'user_phone_field' => 'phone',
    'user_email_field' => 'email',
    'user_auth_field' => 'two_auth_method',
    'otp_reference_number_length' => 6,
    'otp_timeout' => env("OTP_TIMEOUT", 300),
    'otp_digit_length' => 6,
    'encode_password' => true,
    'otp_cookie_timeout' => (365 * 24 * 60 * 60),
    'enquiries_api_env' => env("ENQUIRY_API_ENV", null)
];
