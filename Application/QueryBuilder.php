<?php


namespace Demo\Application;

use Colonel\Configuration;
use ReflectionClass;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class QueryBuilder
{
    const ONE_TO_ONE   = 1;
    const ONE_TO_MANY  = 2;
    const MANY_TO_ONE  = 3;
    const MANY_TO_MANY = 4;

    private $entityMapping = [];

    public function __construct(
        Configuration $configuration
    ) {
        $this->entityMapping = $configuration['entity_mapping'];
        $this->environment   = $configuration['environment'];
    }

    public function buildSelectStatement($fqcn, $criteria)
    {
        $select
            = $join
            = $where
            = null;
        $cols = 1;

        $mapping = new $this->entityMapping[$fqcn];

        // Build the select portion of the query
        $fields = array_map(function($i) use ($mapping) {
            return sprintf('%s.%s AS \'%1$s.%2$s\'', $mapping::table($this->environment), $i);
        }, $mapping::fields());

        $select = 'SELECT ' . implode(', ', $fields);

        $reflection = new ReflectionClass($fqcn);

        foreach ($reflection->getProperties() as $property)
        {

            if (isset($mapping::oneToOne()[$property->getName()]) === true)
            {
            }

            if (isset($mapping::oneToMany()[$property->getName()]) === true)
            {
                foreach ($this->leftJoinBuilder($mapping, self::ONE_TO_MANY) as $relationship) {
                    $select .= $relationship['select'];
                    $join   .= $relationship['join'];
                }
            }

            if (isset($mapping::manyToOne()[$property->getName()]) === true)
            {
            }

            if (isset($mapping::manyToMany()[$property->getName()]) === true)
            {
            }
        }

        foreach ($criteria as $key => $value) {
            $where .= sprintf(
                "%s%s = %s",
                $cols > 1 ? ' AND ' : '',
                $mapping::table($this->environment).'.'.$mapping::fields()[$key],
                $column = ':'.$key
            );
            $data[$column] = $value;
            $cols++;
        }

        return [
            'statement'  => sprintf(
                        "%s FROM %s %s %s",
                        $select,
                        $mapping::table($this->environment),
                        $join !== false ? $join : '',
                        $where !== false ? 'WHERE '.$where : ''
                    ),
            'parameters' => $data,
        ];
    }

    public function  buildInsertStatement($entity, $mappedColumn= [], $buildOnly = false)
    {
        $cascades
            = $map
            = [];
        $mapper   = new $this->entityMapping[get_class($entity)];

        $reflection = new ReflectionClass($entity);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            if (isset($mapper::fields()[$property->getName()]) === false) {

                if (isset($mapper::oneToOne()[$property->getName()]) === true) { }

                if (isset($mapper::oneToMany()[$property->getName()]) === true) {

                    $joinColumn = sprintf(
                        "%s_%s",
                        strtolower($reflection->getShortName()),
                        array_search($mapper::oneToMany()[$property->getName()]['joinColumns']['from'], $mapper::fields())
                    );

                    $mappedProperty = $reflection->getProperty(key($mapper::idMap()));
                    $mappedProperty->setAccessible(true);
                    $map = [
                        'columns' => [
                            $mapper::oneToMany()[$property->getName()]['joinColumns']['to'] => $joinColumn,
                        ],
                        'data'    => [
                            ':'.$joinColumn => $mappedProperty->getValue($entity),
                        ]
                    ];

                    switch(gettype($property->getValue($entity))) {
                        case 'object':
                            // no sure yet...
                            break;
                        case 'array':
                            $cascades = array_map(function($item) use ($map) {
                                return $this->buildInsertStatement($item, $map, true);
                            }, $property->getValue($entity));
                            break;
                    }

                    continue;
                }

                if (isset($mapper::manyToOne()[$property->getName()]) === true) { }

                if (isset($mapper::manyToMany()[$property->getName()]) === true) { }

                continue;
            }

            $value = $property->getValue($entity);

            if (gettype($value) == 'object') {
                $columns[] = sprintf("%s = :%s", $mapper::fields()[$property->getName()], $property->getName());
                $data[':'.$property->getName()] = (string) $value;
                continue;
            }

            $columns[] = sprintf("%s = :%s", $mapper::fields()[$property->getName()], $property->getName());
            $data[':'.$property->getName()] = $property->getValue($entity);
        }

        if (empty($mappedColumn) === false) {
            foreach($mappedColumn['columns'] as $key => $value) {
                $columns[] = sprintf("%s = :%s", $key, $value);
            }
            foreach($mappedColumn['data'] as $key => $value) {
                $data[$key] = $value;
            }
        }

        if ($buildOnly === true) {
            return [
                'query'      => sprintf(
                    "INSERT INTO %s SET %s",
                    $mapper::table()[$this->environment],
                    implode(', ', $columns)
                ),
                'parameters' => $data,
            ];
        }

        return [
            'root' => [
                'query' => sprintf(
                    "INSERT INTO %s SET %s",
                    $mapper::table()[$this->environment],
                    implode(', ', $columns)
                ),
                'parameters' => $data,
            ],
            'cascade' => $cascades,
        ];
    }

    private function leftJoinBuilder($rootMapping, $relationshipType)
    {
        $select
            = $join
            = '';

        switch ($relationshipType) {
            case self::ONE_TO_ONE;
                break;
            case self::ONE_TO_MANY;
                foreach ($rootMapping::oneToMany() as $config) {

                    $mapping = new $this->entityMapping[$config['targetEntity']];

                    $fields = array_map(function($i) use ($mapping) {
                        return sprintf('%s.%s AS \'%1$s.%2$s\'', $mapping::table($this->environment), $i);
                    }, $mapping::fields());

                    return [
                        [
                            'select' => ', ' . implode(', ', $fields),
                            'join'   => sprintf(
                                " LEFT JOIN %s ON %s = %s",
                                $table = $mapping::table($this->environment),
                                $rootMapping::table($this->environment).'.'.$config['joinColumns']['from'],
                                $table.'.'.$config['joinColumns']['to']
                            ),
                        ],
                    ];
                }
                break;
            case self::MANY_TO_ONE;
                break;
            case self::MANY_TO_MANY;
                break;
        }





//        $join .= sprintf(
//            " LEFT JOIN %s ON %s = %s",
//            $mapping['joinTable']['name'],
//            $mapping::table($this->environment).'.'.$mapping['joinTable']['joinColumns']['to'],
//            $mapping['joinTable']['name'].'.'.$mapping['joinTable']['joinColumns']['from']
//        );


//
//
//
//
//echo '<pre>';var_dump($config);die;
//die;
//            if (isset($config['joinTable']['inversedJoinColumns']) === true) {
//                $join .= sprintf(
//                    " LEFT JOIN %s ON %s = %s",
//                    $relMap::table($this->environment),
//                    $config['joinTable']['name'].'.'.$config['joinTable']['inversedJoinColumns']['from'],
//                    $relMap::table($this->environment).'.'.$config['joinTable']['inversedJoinColumns']['to']
//                );
//            }
//        }
//
//        return [
//            'select' => $select,
//            'join'   => $join,
//        ];
    }
}