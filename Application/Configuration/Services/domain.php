<?php

$domainLocation = __DIR__ . '/Domain';

$domain = [
    "domain.customer.customer_service" => [
        "class"     => "Demo\\Domain\\Customer\\CustomerService",
        "arguments" => [
            "application.mediator.service"
        ],
    ],
];

return array_merge(
    $domain,
    require $domainLocation.'/query_handler.php',
    require $domainLocation.'/command_handler.php'
);