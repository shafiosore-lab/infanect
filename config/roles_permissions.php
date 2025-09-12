<?php

return [
    // Permissions are boolean capabilities used in gates. Map role slugs to allowed permissions.
    'mappings' => [
        'super-admin' => [
            'manage_users' => true,
            'manage_providers' => true,
            'approve_providers' => true,
            'approve_documents' => true,
            'manage_financials' => true,
            'configure_system' => true,
        ],

        'provider-professional' => [
            'create_services' => true,
            'manage_services' => true,
            'manage_team' => true,
            'upload_documents' => true,
            'view_financials' => true,
            'access_crm' => true,
        ],

        'provider-bonding' => [
            'create_activities' => true,
            'manage_activities' => true,
            'manage_team' => true,
            'view_financials' => true,
            'access_crm' => true,
            'upload_documents' => false,
        ],

        'client' => [
            'book_services' => true,
            'make_payments' => true,
            'download_documents' => true,
            'upload_documents' => false,
            'manage_team' => false,
        ],
    ],
];
