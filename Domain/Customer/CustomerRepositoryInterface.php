<?php


namespace Demo\Domain\Customer;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
interface CustomerRepositoryInterface
{
    public function create(Customer $customer);

    public function find(CustomerID $id);

    public function update(Customer $customer);
}