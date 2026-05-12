<?php

return [
    'BASEURL' => env('CASTLER_API_BASEURL', 'https://enterprise-api.ncome.in/api/v1'),
    'API_KEY' => env('CASTLER_API_KEY', ''),
    'API_SECRET' => env('CASTLER_API_SECRET', ''),
    'X_API_KEY' => env('CASTLER_X_API_KEY', ''),
    'COMPANY_ESCROW_ACCOUNT' => env('COMPANY_ESCROW_ACCOUNT'),

    // endpoints
    'ENDPOINTS' => [
        # Create Account
        'CREATE_ACCOUNT' => 'escrow',

        # Account Lists
        'ACCOUNT_LIST' => 'account',

        # Get Account Balance
        'ACCOUNT_BALANCE' => 'escrow/balance',

        # Add Source Account
        'ADD_SOURCE_ACCOUNT' => 'escrow/source-account',

        # Activate or Deactivate Source Account
        'TOGGLE_SOURCE_ACCOUNT' => 'escrow/source-account/status',

        # Create Payee
        'CREATE_PAYEE' => 'payee',

        # GET Payee LIST
        'GET_PAYEE_LIST' => 'payee/list',

        # Create Transfer Request
        'CREATE_TRANSFER_REQUEST' => 'transfer',

        # get transfer detail
        'GET_TRANSFER_DETAIL' => 'transfer',
    ],
];