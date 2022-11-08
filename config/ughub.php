<?php

return [

    'environment' => env('UGHUB_ENV', 'test'),
    'test_url' => env('UGHUB_URL', 'https://api-uat.integration.go.ug'),
    'url' => env('UGHUB_URL', 'https://api.integration.go.ug'),

    'nira' => [
        'test_key' => env('UGHUB_NIRA_KEY', 'ClzGubeKPmdlYK2TAuKApWq6QT8a'),
        'test_secret' => env('UGHUB_NIRA_SECRET', 'QH6Gvehb3tsanh3ckThpMkfUZFIa'),
        'test_username' => env('UGHUB_NIRA_USERNAME', 'nita-u@ROOT'),
        'test_password' => env('UGHUB_NIRA_PASSWORD', 'DBsz66!'),
        'test_url' => env('UGHUB_NIRA_URL', 'https://api-uat.integration.go.ug/t/nira.go.ug/nira-api/1.0.0'),

        'key' => env('UGHUB_NIRA_KEY', '4E4GhT49q1aLOw9_r_MYMpVXYJIa'),
        'secret' => env('UGHUB_NIRA_SECRET', 'h1W9eBh1N582GYTWIXal_iK4JrEa'),
        'username' => env('UGHUB_NIRA_USERNAME', 'GnugridAfricaLtd@TPI'),
        'password' => env('UGHUB_NIRA_PASSWORD', '78GI!20h'),
        'url' => env('UGHUB_NIRA_URL', 'https://api.integration.go.ug/t/nira.go.ug/nira-api/1.0.0')
    ],

    'ursb' => [
        'test_key' => env('UGHUB_NIRA_KEY', 'rltUCe29mVief2rSKKYdBMeBJpIa'),
        'test_secret' => env('UGHUB_NIRA_SECRET', 'DG_kWr9m5xcSUhELOy7_s92NnBga'),
        'test_url' => env('UGHUB_NIRA_URL', 'https://api-uat.integration.go.ug/t/ursb.go.ug/ursb-brs-api/1.0.0'),

        'key' => env('UGHUB_NIRA_KEY', 'fDz0HNSBSnIpqQSLFWZSR7252Dwa'),
        'secret' => env('UGHUB_NIRA_SECRET', 'xkl3w7wGsk9sSywzyEnPaReu1pUa'),
        'url' => env('UGHUB_NIRA_URL', 'https://api.integration.go.ug/t/ursb.go.ug/ursb-brs-api/1.0.0')         
    ]
];