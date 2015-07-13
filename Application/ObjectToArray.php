<?php


namespace Demo\Application;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class ObjectToArray
{
    public static function convert($class)
    {
        $reflector = new \ReflectionClass($class);
        $data = [];

        foreach ($reflector->getProperties() as $property) {
            $property->setAccessible(true);

            if (gettype($property->getValue($class)) == 'array') {
                foreach ($property->getValue($class) as $item) {
                    $data[$property->getName()][] = self::convert($item);
                }
            } else {
                $data[$property->getName()] = $property->getValue($class);
            }
        }

        return $data;
    }
}