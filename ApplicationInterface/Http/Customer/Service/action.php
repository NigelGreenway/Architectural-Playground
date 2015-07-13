<?php
return [
    'Customer\\Action\\CustomerListingAction' => [
        'class' => 'Customer\\Action\\CustomerListingAction',
        'arguments' => [
            'domain.customer.customer_service',
            'http.core.response_handler',
        ],
        'methods' => [
            'perform' => [
                'Symfony\\Component\\HttpFoundation\\Request',
            ],
        ],
    ],
    'Customer\\Action\\CustomerRegistrationAction' => [
        'class' => 'Customer\\Action\\CustomerRegistrationAction',
        'arguments' => [
            'domain.customer.customer_service',
            'http.core.action_utility',
            'http.core.response_handler',
        ],
        'methods' => [
            'perform' => [
                'Symfony\\Component\\HttpFoundation\\Request',
            ],
        ],
    ],
    'Customer\\Action\\UpdateEmailAddressAction' => [
        'class' => 'Customer\\Action\\UpdateEmailAddressAction',
        'arguments' => [
            'domain.customer.customer_service',
            'http.core.response_handler',
        ],
        'methods' => [
            'perform' => [
                null,
                'Symfony\\Component\\HttpFoundation\\Request',
            ],
        ],
    ],
    'Customer\\Action\\ViewProfileAction' => [
        'class' => 'Customer\\Action\\ViewProfileAction',
        'arguments' => [
            'domain.customer.customer_service',
            'http.core.response_handler',
        ],
    ],
];