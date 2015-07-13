<?php


namespace Demo\Domain\Customer;

use Demo\Domain\Core\EmailAddress;


/**
 * Class description
 *
 * @package Demo\Domain\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class Customer
{
    private $id,
            $username,
            $firstName,
            $lastNames,
            $emailAddress
    ;

    private function __construct(
        CustomerID   $id,
                     $username,
                     $firstName,
                     $lastNames,
        EmailAddress $emailAddress
    ) {
        $this->id           = $id;
        $this->username     = $username;
        $this->firstName    = $firstName;
        $this->lastNames    = $lastNames;
        $this->emailAddress = $emailAddress;
    }

    public static function register(
        CustomerID   $id,
                     $username,
                     $firstName,
                     $lastNames,
        EmailAddress $emailAddress
    ) {
        return new self($id, $username, $firstName, $lastNames, $emailAddress);
    }

    public function emailAddress()
    {
        return $this->emailAddress;
    }

    public function updateEmailAddress(EmailAddress $emailAddress)
    {
        if ($this->emailAddress->sameAs($emailAddress)) {
            throw new \Exception('Your email address is already ['.(string) $emailAddress .']');
        }

        $this->emailAddress = $emailAddress;
    }
}