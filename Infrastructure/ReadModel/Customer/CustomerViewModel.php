<?php


namespace Demo\Infrastructure\ReadModel\Customer;

use Demo\Infrastructure\ReadModel\AbstractViewModel;

/**
 * Class description
 *
 * @package Demo\Infrastructure\ReadModel\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerViewModel extends AbstractViewModel
{
    public $id,
           $firstName,
           $lastNames,
           $username,
           $emailAddress
    ;
}