<?php


namespace Demo\Infrastructure\Bus\QueryHandler\Customer;

use Demo\Application\QueryHandlerInterface;
use Demo\Application\QueryInterface;
use Demo\Infrastructure\Adapter\MySQLReadAdapter;
use Demo\Infrastructure\ReadModel\Customer\CustomerViewModel;
use Demo\Infrastructure\ReadModel\Customer\CustomerViewModelCollection;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Bus\QueryHandler\Employee
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerListingQueryHandler implements QueryHandlerInterface
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
            ->fetchAll("SELECT
                        id
                        , first_name
                        , last_names
                        , username
                        , email_address
                    FROM
                        customers
                ",
                []
            );

        if ($viewData === false) {
            throw new \Exception(sprintf('No results for given id [%s]', $query->getId()));
        }

        $customerCollection = new CustomerViewModelCollection();

        foreach ($viewData as $item) {
            $customerCollection->add(new CustomerViewModel([
                'id'           => $item['id'],
                'firstName'    => $item['first_name'],
                'lastNames'    => $item['last_names'],
                'username'     => $item['username'],
                'emailAddress' => $item['email_address'],
            ]));
        }

        return $customerCollection->toArray();
    }
}