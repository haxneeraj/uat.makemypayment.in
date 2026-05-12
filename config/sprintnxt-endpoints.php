<?php

return [
    'base_url' => "https://payments.sprintnxt.in/api/v1/",

    'authorization' => 'authorization/oauth2/token',

    'payout' => 'payout/PAYOUT',
    'payout_status' => 'payout/PAYOUT',

    // Platform source account credentials (set these in .env)
    'api_id'                => env('SPRINTNXT_API_ID', '30008'),
    'account_balance_api_id' => env('SPRINTNXT_ACCOUNT_BALANCE_API_ID', '30003'),
    'bank_id'               => env('SPRINTNXT_BANK_ID'),
    'source_account_number' => env('SPRINTNXT_SOURCE_ACCOUNT_NUMBER'),
];