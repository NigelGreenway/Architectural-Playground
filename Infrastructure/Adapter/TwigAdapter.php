<?php


namespace Demo\Infrastructure\Adapter;

use Colonel\Configuration;
use Colonel\Profiler;
use Demo\Application\RouteGenerator;
use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Class description
 *
 * @package Demo\Infrastructure\Adapter
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class TwigAdapter
{
    private $twig,
            $loader,
            $generator
    ;

    public function __construct(
        Configuration  $configuration,
        RouteGenerator $routeGenerator
    ) {
        $this->initialise($configuration);
        $this->generator = $routeGenerator;
    }

    public function render(
              $view,
        array $parameters = []
    ) {
        return $this->twig->render($view, $parameters);
    }

    public function renderJSON(
        $view,
        $parameters
    ) {
        return $this
            ->twig
            ->render(
                $view,
                json_encode($parameters)
            );
    }

    private function initialise($configuration)
    {
        $this->loader = new Twig_Loader_Filesystem;

        $this->setPaths($configuration['twig']['paths']);

        $this->twig = new Twig_Environment(
            $this->loader,
            $configuration['twig']['options']
        );

        $this->twig->addFunction(new \Twig_SimpleFunction('route', function($alias, array $parameters = [], $absolute = RouteGenerator::RELATIVE) {
            return $this->generator->generateRoute($alias, $parameters, $absolute);
        }));
        $this->twig->addFunction(new \Twig_SimpleFunction('getRoutes', function() use ($configuration) {
            $routes = [];
            foreach ($configuration['routes'] as $modules) {
                foreach ($modules as $key => $module) {
                    if (
                        isset($module['expose']) === true
                        && $module['expose'] === true
                    )
                    {
                        preg_match_all(RouteGenerator::$regex, $module['pattern'], $matches);
                        $routes[$key] = [
                            'pattern'    => $module['pattern'],
                            'parameters' => $matches,
                        ];
                    }
                }
            }

            return json_encode($routes);
        }));

        if ($configuration['debug'] === true) {
            $this->twig->addFunction(new \Twig_SimpleFunction('dump', function() {
                echo '<pre>';
                var_dump(func_get_args());
                echo '</pre>';
            }));
        }
    }

    private function setPaths(
        array $paths = []
    ) {
        foreach ($paths as $namespace => $path) {
            $this
                ->loader
                ->addPath(
                    $path,
                    $namespace
            );
        }
    }
}