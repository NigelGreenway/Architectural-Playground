<?php


namespace Demo\Infrastructure\Repository\Customer;

use Demo\Domain\Customer\Customer;
use Demo\Domain\Customer\CustomerID;
use Demo\Domain\Customer\CustomerRepositoryInterface;
use Demo\Infrastructure\Adapter\MySQLWriteAdapter;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Repository\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerRepository implements CustomerRepositoryInterface
{
    private $connection;

    public function __construct(
        MySQLWriteAdapter $connection
    ) {
        $this->connection = $connection;
    }

    public function create(Customer $customer)
    {
        $this->connection->insert($customer);
        $this->connection->flush();
    }

    public function find(CustomerID $id)
    {
        return $this->connection->find(
            Customer::class,
            [
                'id' => (string) $id,
            ]
        );
    }

    public function update(Customer $customer)
    {
        $this->connection->update($customer);
        $this->connection->flush();
    }
}