<?php
$servicesLocation = __DIR__ ;

$services = array_merge(
    require $servicesLocation.'/adapter.php',
    require $servicesLocation.'/application.php',
    require $servicesLocation.'/domain.php',
    require $moduleLocation.'/Core/Service/service.php',
    require $moduleLocation.'/Customer/Service/service.php'
);

return [
    'di' => $services,
];