<?php

return [
    'API_KEY' => env('FAST2SMS_API_KEY'),
    'SENDER_ID' => env('FAST2SMS_SENDER_ID', 'MMPPAY'),
    'BASEURL' => 'https://www.fast2sms.com/',

    'ENDPOINTS' => [
        'SEND' => 'dev/bulkV2'
    ]
];