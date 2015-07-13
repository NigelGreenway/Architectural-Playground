<?php

return [
    'Demo\\Infrastructure\\Bus\\CommandHandler\\Customer\\RegisterCustomerCommandHandler' => [
        'class' => 'Demo\\Infrastructure\\Bus\\CommandHandler\\Customer\\RegisterCustomerCommandHandler',
        'arguments' => [
            'customer.repository',
            'customer_registration.factory',
        ],
    ],
];