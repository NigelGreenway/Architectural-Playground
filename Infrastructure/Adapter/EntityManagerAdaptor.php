<?php

namespace Demo\Infrastructure\Adapter;

use Colonel\Configuration;
use Doctrine\Common\Persistence\Mapping\Driver\PHPDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Class description
 *
 * @package Demo\Infrastructure\Adapter
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class EntityManagerAdaptor
{
    private $entityManager;

    public function __construct(
        Configuration $configuration
    ) {
        $driver = new PHPDriver(__DIR__ . '/../Mapping/');
        $config = Setup::createConfiguration($configuration['debug']);
        $config->setMetadataDriverImpl($driver);

        $this->entityManager = EntityManager::create(
            $configuration['database']['write_connection'],
            $config
        );
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}