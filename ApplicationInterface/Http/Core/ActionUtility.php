<?php


namespace Core;

use Demo\Application\RouteGenerator;


/**
 * Class description
 *
 * @package Core
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class ActionUtility
{
    private $routing;

    public function __construct(
        RouteGenerator $routeGenerator
    ) {
        $this->routing = $routeGenerator;
    }

    public function redirect($route)
    {
        header('Location: '.$route);
        exit;
    }

    public function generateRoute($alias, array $parameters = [], $absolute = RouteGenerator::RELATIVE)
    {
        return $this->routing->generateRoute($alias, $parameters, $absolute);
    }
}