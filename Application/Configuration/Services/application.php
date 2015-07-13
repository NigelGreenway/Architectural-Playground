<?php

return [
    "application.route_generator" => [
        "class" => "Demo\\Application\\RouteGenerator",
        "arguments" => [
            "Colonel\\Configuration",
        ],
    ],
    "application.mediator.service" => [
        "class" => "Demo\\Application\\Mediator",
        "arguments" => [
            "Colonel\\Configuration",
            "League\\Container\\Container",
        ],
    ],
    "application.query_builder.service" => [
        "class" => "Demo\\Application\\QueryBuilder",
        "arguments" => [
            "Colonel\\Configuration",
        ],
    ],
    "customer.repository" => [
        "class" => "Demo\\Infrastructure\\Repository\\Customer\\CustomerRepository",
        "arguments" => [
            "scafell.mysql_write.adapter",
        ],
    ],
    "customer_registration.factory" => [
        "class" => "Demo\\Infrastructure\\Factory\\Customer\\CustomerRegistrationFactory",
    ],
];