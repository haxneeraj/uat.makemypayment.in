<?php 

return [
    'roles' => [
        ['name' => 'super-admin', 'label' => 'Super Admin'],
        ['name' => 'merchant', 'label' => 'Merchant'],
    ],

    'permissions' => [
        'super-admin' => [
            // KYC Management
            [
                'name' => 'read-pending-kyc',
                'label' => 'Read Pending KYC',
                'group_name' => 'KYC Management',
            ],
            [
                'name' => 'approve-pending-kyc',
                'label' => 'Approve Pending KYC',
                'group_name' => 'KYC Management',
            ],
            [
                'name' => 'reject-pending-kyc',
                'label' => 'Reject Pending KYC',
                'group_name' => 'KYC Management',
            ],

            // Source Account Verification Management
            [
                'name' => 'read-pending-source-account-verifications',
                'label' => 'Read Pending Source Account Verifications',
                'group_name' => 'Source Account Verification Management',
            ],
            [
                'name' => 'approve-pending-source-account-verifications',
                'label' => 'Approve Pending Source Account Verifications',
                'group_name' => 'Source Account Verification Management',
            ],
            [
                'name' => 'reject-pending-source-account-verifications',
                'label' => 'Reject Pending Source Account Verifications',
                'group_name' => 'Source Account Verification Management',
            ],

            // IP & Webhook Verification Management
            [
                'name' => 'read-ip-and-webhook-verifications',
                'label' => 'Read IP & Webhook Verifications',
                'group_name' => 'IP & Webhook Verification Management',
            ],
            [
                'name' => 'approve-ip-and-webhook-verifications',
                'label' => 'Approve IP & Webhook Verifications',
                'group_name' => 'IP & Webhook Verification Management',
            ],
            [
                'name' => 'reject-ip-and-webhook-verifications',
                'label' => 'Reject IP & Webhook Verifications',
                'group_name' => 'IP & Webhook Verification Management',
            ],

            // Merchants Management
            [
                'name' => 'read-merchants',
                'label' => 'Read Merchants',
                'group_name' => 'Merchant Management',
            ],
            [
                'name' => 'edit-merchants',
                'label' => 'Edit Merchants',
                'group_name' => 'Merchant Management',
            ],
            [
                'name' => 'suspend-merchants',
                'label' => 'Suspend Merchants',
                'group_name' => 'Merchant Management',
            ],
            [
                'name' => 'activate-merchants',
                'label' => 'Activate Merchants',
                'group_name' => 'Merchant Management',
            ],

            // Payouts            
            [
                'name' => 'read-payouts',
                'label' => 'Read Payouts',
                'group_name' => 'Payout Management',
            ],

            // Reports Management            
            [
                'name' => 'read-reports',
                'label' => 'Read Reports',
                'group_name' => 'Report Management',
            ],

            // Roles Management            
            [
                'name' => 'read-roles',
                'label' => 'Read Roles',
                'group_name' => 'Role Management',
            ],
            [
                'name' => 'create-roles',
                'label' => 'Create Roles',
                'group_name' => 'Role Management',
            ],
            [
                'name' => 'edit-roles',
                'label' => 'Edit Roles',
                'group_name' => 'Role Management',
            ],
            [
                'name' => 'delete-roles',
                'label' => 'Delete Roles',
                'group_name' => 'Role Management',
            ],

            // Staff Management
            [
                'name' => 'read-staff',
                'label' => 'Read Staff',
                'group_name' => 'Staff Management',
            ],
            [
                'name' => 'create-staff',
                'label' => 'Create Staff',
                'group_name' => 'Staff Management',
            ],
            [
                'name' => 'edit-staff',
                'label' => 'Edit Staff',
                'group_name' => 'Staff Management',
            ],
            [
                'name' => 'delete-staff',
                'label' => 'Delete Staff',
                'group_name' => 'Staff Management',
            ],

            // Category Management
            [
                'name' => 'read-categories',
                'label' => 'Read Categories',
                'group_name' => 'Category Management',
            ],
            [
                'name' => 'create-categories',
                'label' => 'Create Categories',
                'group_name' => 'Category Management',
            ],
            [
                'name' => 'edit-categories',
                'label' => 'Edit Categories',
                'group_name' => 'Category Management',
            ],
            [
                'name' => 'delete-categories',
                'label' => 'Delete Categories',
                'group_name' => 'Category Management',
            ],

            // SubCategory Management
            [
                'name' => 'read-sub-categories',
                'label' => 'Read Sub Categories',
                'group_name' => 'SubCategory Management',
            ],
            [
                'name' => 'create-sub-categories',
                'label' => 'Create Sub Categories',
                'group_name' => 'SubCategory Management',
            ],
            [
                'name' => 'edit-sub-categories',
                'label' => 'Edit Sub Categories',
                'group_name' => 'SubCategory Management',
            ],
            [
                'name' => 'delete-sub-categories',
                'label' => 'Delete Sub Categories',
                'group_name' => 'SubCategory Management',
            ],

            // Settings Management
            [
                'name' => 'read-settings',
                'label' => 'Read Settings',
                'group_name' => 'Setting Management',
            ]
        ],

        'merchant' => [
            [
                'name' => 'read-settings',
                'label' => 'Read Settings',
                'group_name' => 'Setting Management'
            ],
        ],
    ],
    
];