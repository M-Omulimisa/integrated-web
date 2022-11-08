<?php
return [
    'server' => [
        'domain' => env('DOMAIN'),
    ],

    'sms' => [
        'url'       => env('SMS_GATEWAY_URL'),
        'api_key'   =>env('SMS_API_KEY')
    ],

    // AfricaIsTalking username and API key
    'ait' => [
        'username'  => env('AIT_USERNAME'),
        'key'       =>env('AIT_API_KEY')
    ],

    'otp_expiry_minutes'=>env('OTP_EXPIRY_MINUTES'),

    'app' => [
        'is_human_date_format'  => true,
        'date_format'       => 'l jS F Y (h:i:s)',
        'customer_care_email' => 'support@omulimisa.com'
    ],

];
