<?php


namespace Demo\Infrastructure\ReadModel;

use JsonSerializable;

/**
 * Class description
 *
 * @package Demo\Infrastructure\ReadModel
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
class AbstractViewModel implements JsonSerializable
{
    public function __construct(array $values = [])
    {
        $class = new \ReflectionClass($this);

        foreach ($class->getProperties() as $property) {
            $property->setAccessible(true);
            $property->setValue($this, $values[$property->getName()]);
        }
    }

    public function toArray()
    {
        $data = [];

        $class = new \ReflectionClass($this);

        foreach( $class->getProperties() as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}