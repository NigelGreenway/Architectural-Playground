<?php

$moduleLocation = __DIR__.'/../../ApplicationInterface/Http';

$debug = strtolower(getenv('DEBUG')) == 'true' ? true : false;

$config = [
    'debug'       => $debug,
    'environment' => getenv('ENVIRONMENT'),
    'database'    => [
        'read_connection' => [
            'host'     => getenv('DATABASE_READ_CONNECTION_HOST'),
            'dbname'   => getenv('DATABASE_READ_CONNECTION_DBNAME'),
            'user'     => getenv('DATABASE_READ_CONNECTION_USER'),
            'password' => getenv('DATABASE_READ_CONNECTION_PASSWORD'),
            'driver'   => getenv('DATABASE_READ_CONNECTION_DRIVE'),
        ],
        'write_connection' => [
            'host'     => getenv('DATABASE_WRITE_CONNECTION_HOST'),
            'dbname'   => getenv('DATABASE_WRITE_CONNECTION_DBNAME'),
            'user'     => getenv('DATABASE_WRITE_CONNECTION_USER'),
            'password' => getenv('DATABASE_WRITE_CONNECTION_PASSWORD'),
            'driver'   => getenv('DATABASE_WRITE_CONNECTION_DRIVE'),
        ],
    ],
    'kernel'      => [
        'root' => realpath(__DIR__.'/../..'),
    ],
    'routes'          => require __DIR__.'/routing.php',
    'services'        => require __DIR__.'/services/services.php',
    'entity_mapping'  => require __DIR__.'/Mapping/entity_mapping.php',
    'query_mapping'   => require __DIR__.'/Mapping/query_mapping.php',
    'command_mapping' => require __DIR__.'/Mapping/command_mapping.php',
    'twig'            => require __DIR__.'/twig.php',
];

return array_merge_recursive(
    $config
);