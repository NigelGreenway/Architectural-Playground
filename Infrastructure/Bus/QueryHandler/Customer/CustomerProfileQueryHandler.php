<?php


namespace Demo\Infrastructure\Bus\QueryHandler\Customer;

use Demo\Application\QueryHandlerInterface;
use Demo\Application\QueryInterface;
use Demo\Infrastructure\Adapter\MySQLReadAdapter;
use Demo\Infrastructure\ReadModel\Customer\CustomerViewModel;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Bus\QueryHandler\Employee
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerProfileQueryHandler implements QueryHandlerInterface
{
    private $connection;

    public function __construct(
        MySQLReadAdapter $connection
    ) {
        $this->connection = $connection;
    }

    public function handle(QueryInterface $query)
    {
        $viewData = $this
            ->connection
            ->fetch("SELECT
                    id
                    , first_name
                    , last_names
                    , username
                    , email_address
                FROM
                    customers
                WHERE
                    id = :id
            ",
            [
                ':id' => $query->getId(),
            ]);

        if ($viewData === false) {
            throw new \Exception(sprintf('No results for given id [%s]', $query->getId()));
        }

        return (new CustomerViewModel([
            'id'           => $viewData['id'],
            'firstName'    => $viewData['first_name'],
            'lastNames'    => $viewData['last_names'],
            'username'     => $viewData['username'],
            'emailAddress' => $viewData['email_address'],
        ]))->toArray();
    }
}