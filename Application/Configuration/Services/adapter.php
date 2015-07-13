<?php

return [
    "scafell.mysql_read.adapter" => [
        "class" => "Demo\\Infrastructure\\Adapter\\MySQLReadAdapter",
        "arguments" => [
            "Colonel\\Configuration",
        ],
        "singleton" => true,
    ],
    "scafell.mysql_write.adapter" => [
        "class" => "Demo\\Infrastructure\\Adapter\\MySQLWriteAdapter",
        "arguments" => [
            "Colonel\\Configuration",
            "application.query_builder.service",
        ],
    ],
    "scafell.twig.adapter" => [
        "class" => "Demo\\Infrastructure\\Adapter\\TwigAdapter",
        "arguments" => [
            "Colonel\\Configuration",
            "Demo\\Application\\RouteGenerator",
        ],
    ],
];