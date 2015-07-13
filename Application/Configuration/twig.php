<?php

return [
    'paths' => [
        'Base'     => $moduleLocation . '/Core/View',
        'Customer' => $moduleLocation . '/Customer/Template',
    ],
    'options' => [
        'debug'            => $debug,
        'cache'            => realpath(__DIR__ . '/../../var/Cache/Twig'),
        'strict_variables' => $debug,
        'auto_reload'      => true,
        'autoescape'       => true,
    ],
];