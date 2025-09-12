<?php

return [
    'types' => [
        [
            'slug' => 'provider-professional',
            'name' => '🩺 Professional Provider - Healthcare, Therapy, Medical Services',
            'description' => 'Licensed healthcare professionals, therapists, counselors',
            'required_documents' => ['id_document', 'professional_license', 'insurance_certificate'],
            'dashboard_route' => 'dashboard.provider-professional'
        ],
        [
            'slug' => 'provider-bonding',
            'name' => '🤝 Bonding Provider - Community & Family Activities',
            'description' => 'Community organizers, family activity coordinators',
            'required_documents' => ['id_document', 'background_check'],
            'dashboard_route' => 'dashboard.provider-bonding'
        ],
        [
            'slug' => 'provider-organization',
            'name' => '🏢 Organization Provider - Companies, NGOs, Institutions',
            'description' => 'Organizations offering services or activities',
            'required_documents' => ['business_registration', 'tax_certificate', 'insurance_certificate'],
            'dashboard_route' => 'dashboard.provider-professional'
        ],
        [
            'slug' => 'provider-freelancer',
            'name' => '💼 Freelance Provider - Independent Service Providers',
            'description' => 'Independent contractors, consultants, specialists',
            'required_documents' => ['id_document', 'portfolio', 'references'],
            'dashboard_route' => 'dashboard.provider-professional'
        ],
        [
            'slug' => 'provider-educator',
            'name' => '🎓 Educational Provider - Teachers, Trainers, Coaches',
            'description' => 'Educational professionals, trainers, life coaches',
            'required_documents' => ['id_document', 'teaching_certificate', 'background_check'],
            'dashboard_route' => 'dashboard.provider-professional'
        ]
    ],

    'kyc_documents' => [
        'id_document' => [
            'name' => 'Government Issued ID / Passport',
            'required' => true,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'professional_license' => [
            'name' => 'Professional License / Certificate',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'business_registration' => [
            'name' => 'Business Registration Certificate',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'insurance_certificate' => [
            'name' => 'Professional Liability Insurance',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'tax_certificate' => [
            'name' => 'Tax Registration Certificate',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'background_check' => [
            'name' => 'Criminal Background Check',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'teaching_certificate' => [
            'name' => 'Teaching / Training Certificate',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png',
            'max_size' => '5MB'
        ],
        'portfolio' => [
            'name' => 'Work Portfolio / References',
            'required' => false,
            'accept' => '.pdf,.jpg,.jpeg,.png,.doc,.docx',
            'max_size' => '10MB'
        ],
        'references' => [
            'name' => 'Professional References',
            'required' => false,
            'accept' => '.pdf,.doc,.docx',
            'max_size' => '5MB'
        ]
    ],

    'approval_statuses' => [
        'pending' => 'Pending Review',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'suspended' => 'Suspended'
    ]
];
