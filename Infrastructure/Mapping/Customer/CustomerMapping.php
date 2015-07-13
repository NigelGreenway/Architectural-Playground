<?php

namespace Demo\Infrastructure\Mapping\Customer;

use Demo\Application\MappingInterface;
use Demo\Domain\Core\EmailAddress;
use Demo\Domain\Customer\Customer;


final class CustomerMapping implements MappingInterface
{
    private static $tables = [
        'production' => 'customers',
        'publishing' => 'customers',
    ];

    public static function table($environment = false)
    {
        if ($environment !== false) {
            return self::$tables[$environment];
        }

        return self::$tables;
    }

    public static function entity()
    {
        return Customer::class;
    }

    public static function idMap()
    {
        return [
            'id' => 'id',
        ];
    }

    public static function fields()
    {
        return [
            'id'           => 'id',
            'firstName'    => 'first_name',
            'lastNames'    => 'last_names',
            'username'     => 'username',
            'emailAddress' => 'email_address',
        ];
    }

    public static function valueObjects()
    {
        return [
            'emailAddress' =>  EmailAddress::class,
        ];
    }

    public static function oneToOne()
    {
        return [];
    }

    public static function oneToMany()
    {
        return [];
    }

    public static function manyToOne()
    {
        return [];
    }

    public static function manyToMany()
    {
        return [];
    }
}