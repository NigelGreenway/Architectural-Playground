<?php

namespace Demo\Infrastructure\Adapter;

use Colonel\Configuration;
use PDO;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Adapter
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class MySQLReadAdapter
{
    private $connection = [];

    public function __construct(
        Configuration $configuration
    ) {
        $this->connection = new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s',
                $configuration['database']['read_connection']['host'],
                $configuration['database']['read_connection']['dbname']
            ),
            $configuration['database']['read_connection']['user'],
            $configuration['database']['read_connection']['password']
        );

        if ($configuration['debug'] === true) {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function fetch($statement, $parameters = [], $fetchType = PDO::FETCH_ASSOC)
    {
        $query = $this
            ->connection
            ->prepare($statement);

        $query
            ->execute($parameters);

        return $query->fetch($fetchType);
    }

    /**
     * @param string $statement
     * @param array  $parameters
     * @param int    $fetchType
     *
     * @return array
     */
    public function fetchAll($statement, $parameters = [], $fetchType = PDO::FETCH_ASSOC)
    {
        $query = $this
            ->connection
            ->prepare($statement);
        $query
            ->execute($parameters);

        return $query->fetchAll($fetchType);
    }
}