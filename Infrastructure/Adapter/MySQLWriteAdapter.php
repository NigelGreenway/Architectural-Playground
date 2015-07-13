<?php

namespace Demo\Infrastructure\Adapter;

use Colonel\Configuration;
use Demo\Application\Hydrator;
use Demo\Application\QueryBuilder;
use ReflectionClass;
use PDO;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Adapter
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class MySQLWriteAdapter
{
    private $environment;
    private $entityMapping;
    private $connection = [];

    public function __construct(
        Configuration $configuration,
        QueryBuilder  $queryBuilder
    ) {
        $this->connection = new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s',
                $configuration['database']['write_connection']['host'],
                $configuration['database']['write_connection']['dbname']
            ),
            $configuration['database']['write_connection']['user'],
            $configuration['database']['write_connection']['password']
        );
        if ($configuration['debug'] === true) {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $this->entityMapping = $configuration['entity_mapping'];
        $this->environment   = $configuration['environment'];
        $this->queryBuilder  = $queryBuilder;
    }

    public function execute($statement, $parameters)
    {
        $query = $this
            ->connection
            ->prepare($statement);
        $query->execute($parameters);
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

    public function fetchAll($statement, $parameters = [], $fetchType = PDO::FETCH_ASSOC)
    {
        $query = $this
            ->connection
            ->prepare($statement);
        $query
            ->execute($parameters);

        return $query->fetchAll($fetchType);
    }

    public function find($fqcn, $criteria)
    {
        $query = $this->queryBuilder->buildSelectStatement($fqcn, $criteria);
        $results = $this->fetchAll($query['statement'], $query['parameters']);

        if (empty($results) === true) {
            throw new \Exception('No results found for '.$fqcn.' where columns are ['.implode(',', array_keys($criteria)).'] and values are ['.implode(',', $criteria).']');
        }

        $h = new Hydrator($this->entityMapping, $this->environment);

        return $h->hydrate($fqcn, new $this->entityMapping[$fqcn], $results)[0];
    }

    public function insert($entity)
    {
        $queries = $this->queryBuilder->buildInsertStatement($entity, [], false);

        $this->persist(
            $queries['root']['query'],
            $queries['root']['parameters']
        );

        array_walk($queries['cascade'], function($insert) {
            $this->persist($insert['query'], $insert['parameters']);
        });
    }

    public function remove($entity)
    {
        $mapper = new $this->entityMapping[get_class($entity)];

        $reflection = new ReflectionClass($entity);
        $id         = $reflection->getProperty('id');
        $id->setAccessible(true);

        $this
            ->execute(
                sprintf(
                    "DELETE FROM %s WHERE %s = :id",
                    $mapper::table()[$this->environment],
                    $mapper::idMap()['id']
                ),
                [
                    ':id' => $id->getValue($entity),
                ]
            );
    }

    public function update($entity, $buildOnly = false)
    {
        $cascades = [];
        $mapper   = new $this->entityMapping[get_class($entity)];

        foreach ((new \ReflectionClass($entity))->getProperties() as $property) {
            $property->setAccessible(true);

            if (isset($mapper::fields()[$property->getName()]) === false) {
                if (isset($mapper::relationships()[$property->getName()]) === true) {

                    switch(gettype($property->getValue($entity))) {
                        case 'object':
                            // ...
                            break;
                        case 'array':
                            $cascades = array_map(function($item) {
                                return $this->insert($item, true);
                            }, $property->getValue($entity));
                            break;
                    }
                }
                continue;
            }

            if (isset($mapper::idMap()[$property->getName()]) === true) {
                $id = sprintf("%s = :%s", $mapper::fields()[$property->getName()], $property->getName());
            } else {
                $columns[] = sprintf("%s = :%s", $mapper::fields()[$property->getName()], $property->getName());
            }

            $data[':'.$property->getName()] = (string) $property->getValue($entity);
        }

        if ($buildOnly === true) {
            return [
                'query'      => sprintf(
                    "UPDATE %s SET %s WHERE %s",
                    $mapper::table()[$this->environment],
                    implode(', ', $columns),
                    $id
                ),
                'parameters' => $data,
            ];
        }

        $this->persist(
            sprintf(
                "UPDATE %s SET %s WHERE %s",
                $mapper::table()[$this->environment],
                implode(', ', $columns),
                $id
            ),
            $data
        );

        array_walk($cascades, function($insert) {
            $this->persist($insert['query'], $insert['parameters']);
        });
    }

    public function persist($statement, $parameters)
    {
        if ($this->connection->inTransaction() === false) {
            $this->connection->beginTransaction();
        }

        $this->execute($statement, $parameters);
    }

    public function flush()
    {
        $this->connection->commit();
    }
}