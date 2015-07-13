<?php


namespace Demo\Application;

use Colonel\Configuration;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class RouteGenerator
{
    const ABSOLUTE = true;
    const RELATIVE = false;

    public static $regex = '#\{\w+\}#';

    private $configuration;

    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    public function generateRoute($alias, array $parameters = [], $absolute = self::RELATIVE)
    {
        $route = $this->findRoute($alias);

        $elements = array_map(function($element) use ($parameters) {
            foreach ($parameters as $key => $value) {
                if (preg_match('#\{'.$key.'\}#', $element, $matches)) {
                    return urlencode(preg_replace('#\{'.$key.'\}#', $value, $matches[0]));
                }
            }
            return $element;
        }, explode('/', $route['pattern']));

        $generatedRoute = implode('/', $elements);

        $this->validateParameters($alias, $generatedRoute);

        if ($absolute === self::ABSOLUTE) {
            return sprintf(
                '%s://%s%s',
                isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http',
                $_SERVER['HTTP_HOST'],
                $generatedRoute
            );
        }

        return sprintf(
            '%s',
            $generatedRoute
        );
    }

    private function findRoute($routeAlias)
    {
        foreach($this->configuration['routes'] as $module) {
            if (array_key_exists($routeAlias, $module)) {
                return $module[$routeAlias];
            }
        }

        throw new \Exception(
            sprintf(
                'The route [%s] does not exists. Please check your configuration file[s].',
                $routeAlias
            )
        );
    }

    /**
     * Validate the passed parameters against the routes actual parameters
     *
     * @param string $alias
     * @param string $generatedRoute
     *
     * @throws \Exception
     *
     * @returns void
     */
    private function validateParameters($alias, $generatedRoute)
    {
        preg_match_all(self::$regex, $generatedRoute, $missingParameters);

        if (count($missingParameters[0]) > 0) {
            $required = array_map(function($parameter) use ($alias) {
                return $parameter;
            }, $missingParameters[0]);

            throw new \Exception(
                sprintf(
                    'The route alias [%s] requires the parameters [%s].',
                    $alias,
                    implode(',', $required)
                )
            );
        }
    }
}