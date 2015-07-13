<?php

return [
    'customer_registration' => [
        'pattern'    => '/customers/register',
        'controller' => 'Customer\\Action\\CustomerRegistrationAction::perform',
        'method'     => ['GET', 'POST'],
    ],
    'customer_listing' => [
        'pattern'    => '/customers',
        'controller' => 'Customer\\Action\\CustomerListingAction::perform',
        'method'     => 'GET',
    ],
    'customer_profile' => [
        'pattern'    => '/customers/{id}',
        'controller' => 'Customer\\Action\\ViewProfileAction::perform',
        'method'     => 'GET',
    ],
    'customer_change_email_address' => [
        'pattern'    => '/customers/{id}/update/email_address',
        'controller' => 'Customer\\Action\\UpdateEmailAddressAction::perform',
        'method'     => 'PUT',
        'expose'     => true,
    ],
];