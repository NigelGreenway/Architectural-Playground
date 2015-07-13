<?php

return [
    "http.core.response_handler" => [
        "class" => "Core\\ResponseHandler\\ResponseHandler",
        "arguments" => [
            "scafell.twig.adapter",
        ],
    ],
    "http.core.action_utility" => [
        "class" => "Core\\ActionUtility",
        "arguments" => [
            "application.route_generator",
        ],
    ],
];