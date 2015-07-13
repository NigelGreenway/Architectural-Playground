<?php


namespace Demo\Application;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
interface MappingInterface
{
    public static function table();

    public static function entity();

    public static function idMap();

    public static function fields();

    public static function oneToOne();

    public static function oneToMany();

    public static function manyToOne();

    public static function manyToMany();
}