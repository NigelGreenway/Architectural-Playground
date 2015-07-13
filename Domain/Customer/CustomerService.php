<?php


namespace Demo\Domain\Customer;

use Demo\Application\MediatorInterface;
use Demo\Domain\Core\DomainServiceInterface;
use Demo\Domain\Core\EmailAddress;
use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class CustomerService implements DomainServiceInterface
{
    private $mediator;

    public function __construct(
        MediatorInterface $mediator
    ) {
        $this->mediator = $mediator;
    }

    public function getCustomerListing()
    {
        return [
            'customers' => $this->mediator->request(new CustomerListingQuery)
        ];
    }

    public function getProfileById(CustomerID $id)
    {
        return [
            'customer' => $this->mediator->request(new CustomerProfileQuery((string) $id))
        ];
    }

    public function updateEmailAddress(
        CustomerID   $id,
        EmailAddress $emailAddress
    ) {
        $this
            ->mediator
            ->execute(
                new UpdateCustomerEmailCommand($id, $emailAddress)
            );

        return [
            'newEmailAddress' => (string) $emailAddress,
            'message'         => sprintf("Your email has been changed to '%s'.", (string) $emailAddress),
        ];
    }

    public function register(ParameterBag $payload)
    {
        $this
            ->mediator
            ->execute(
                new RegisterCustomerCommand(
                    $customerID = CustomerID::generate(),
                    $payload->get('firstName'),
                    $payload->get('lastNames'),
                    $payload->get('username'),
                    EmailAddress::fromNative($payload->get('emailAddress'))
                )
            );

        return [
            'customerID' => (string) $customerID,
            'name' => $payload->get('firstName') . ' ' . $payload->get('lastNames'),
        ];
    }
}