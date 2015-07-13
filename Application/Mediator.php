<?php


namespace Demo\Application;

use Colonel\Configuration;
use League\Container\Container;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class Mediator implements MediatorInterface
{
    private $container;
    private $queryNameToHandlersMap;
    private $commandNameToHandlersMap;

    public function __construct(
        Configuration $configuration,
        Container     $container
    ) {
        $this->container    = $container;

        $this->addQueryHandlers(
            isset($configuration['query_mapping']) === true ? $configuration['query_mapping'] : []
        );
        $this->addCommandHandlers(
            isset($configuration['command_mapping']) === true ? $configuration['command_mapping'] : []
        );
    }

    public function request(QueryInterface $query)
    {
        $className = get_class($query);

        if (array_key_exists($className, $this->queryNameToHandlersMap) === false) {
            throw new \Exception(sprintf('Query Map not found [%s]', $className));
        }

        $handler = $this->container->get($this->queryNameToHandlersMap[$className]);
        return $handler->handle($query);
    }

    public function execute(CommandInterface $command)
    {
        $className = get_class($command);

        if (array_key_exists($className, $this->commandNameToHandlersMap) === false) {
            throw new \Exception(sprintf('Command Map not found [%s]', $className));
        }

        $handler = $this->container->get($this->commandNameToHandlersMap[$className]);
        return $handler->execute($command);
    }

    private function addQueryHandlers(array $queryNameToHandlerMap = [])
    {
        $this->queryNameToHandlersMap = $queryNameToHandlerMap;
    }

    private function addCommandHandlers(array $commandNameToHandlerMap = []) {
        $this->commandNameToHandlersMap = $commandNameToHandlerMap;
    }
}