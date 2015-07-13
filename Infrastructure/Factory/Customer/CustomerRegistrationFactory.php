<?php


namespace Demo\Infrastructure\Factory\Customer;

use Demo\Domain\Core\EmailAddress;
use Demo\Domain\Customer\Customer;
use Demo\Domain\Customer\CustomerID;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerRegistrationFactory
{
    public static function make(
        CustomerID   $id,
                     $firstName,
                     $lastNames,
                     $username,
        EmailAddress $emailAddress
    ) {
        return Customer::register(
            $id,
            $username,
            $firstName,
            $lastNames,
            $emailAddress
        );
    }
}