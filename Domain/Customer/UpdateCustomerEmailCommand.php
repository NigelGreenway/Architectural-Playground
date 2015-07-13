<?php


namespace Demo\Domain\Customer;

use Demo\Application\CommandInterface;
use Demo\Domain\Core\EmailAddress;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class UpdateCustomerEmailCommand implements CommandInterface
{
    private $customerId,
            $emailAddress
    ;

    public function __construct(
        CustomerID   $customerId,
        EmailAddress $emailAddress
    ) {
        $this->customerId   = $customerId;
        $this->emailAddress = $emailAddress;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
}