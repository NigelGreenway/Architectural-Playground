<?php

namespace Demo\Application;

use Colonel\Configuration;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class Hydrator
{
    private $mapping;

    public function __construct(
        array         $mapping,
                      $environment
    ) {
        $this->mapping     = $mapping;
        $this->environment = $environment;
    }

    public function hydrate(
        $fqcn,
        MappingInterface $mapping,
        array            $data
    ) {
        if ($mapping::entity() != $fqcn) {
            throw new MappingConfigurationMismatchException($fqcn, $mapping);
        }

        $reflector = new \ReflectionClass($fqcn);

        if (key($data) === 0) {
            $results[] = $this->builder(
                $reflector,
                $mapping,
                $data
            );
            return $results;
        }

        return $this->builder(
            $reflector,
            $mapping,
            $data
        );
    }

    private function builder($reflector, $mapping, $data)
    {
        $object = $reflector->newInstanceWithoutConstructor();

        foreach($reflector->getProperties() as $property) {
            $property->setAccessible(true);

            if (isset($mapping::oneToOne()[$property->getName()]) === true) {
                continue;
            }

            if (isset($mapping::oneToMany()[$property->getName()]) === true) {
                $map = $mapping::oneToMany()[$property->getName()];
                $property->setValue(
                    $object,
                    $this->getCollection($map['targetEntity'], new $this->mapping[$map['targetEntity']], $data)
                );
                continue;
            }

            if (isset($mapping::manyToOne()[$property->getName()]) === true) {
                continue;
            }

            if (isset($mapping::manyToMany()[$property->getName()]) === true) {
                continue;
            }

            if (isset($mapping::valueObjects()[$property->getName()]) === true) {
                $valueObject = $mapping::valueObjects()[$property->getName()];

                $property->setValue(
                    $object,
                    $valueObject::fromNative(
                        $data[0][$mapping::table($this->environment).'.'.$mapping::fields()[$property->getName()]]
                    )
                );

                continue;
            }

            $property->setValue(
                $object,
                $data[0][$mapping::table($this->environment).'.'.$mapping::fields()[$property->getName()]]
            );
        }

        return $object;
    }

    private function getCollection($fqcn, $mapping, $data)
    {
        $collection = [];

        foreach ($data as $key => $row) {
            $collection[
                $key
            ] = $this->hydrate($fqcn, $mapping, $data);
        }

        return $collection;
    }
}