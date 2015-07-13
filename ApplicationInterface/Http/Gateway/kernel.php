<?php

$root = __DIR__ . '/../../..';

require $root .'/vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv($root);
$dotenv->load();

$configuration = require_once $root . '/Application/Configuration/configuration.php';

if ($configuration['debug'] === true) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$app = (new Colonel\HttpKernel(
    $configuration
))->run();