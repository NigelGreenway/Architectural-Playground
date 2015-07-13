<?php

namespace Demo\Infrastructure\Bus\CommandHandler\Customer;

use Demo\Application\CommandHandlerInterface;
use Demo\Application\CommandInterface;
use Demo\Domain\Customer\CustomerRepositoryInterface;
use Demo\Infrastructure\Factory\Customer\CustomerRegistrationFactory;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Bus\CommandHandler\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class RegisterCustomerCommandHandler implements CommandHandlerInterface
{
    private $customerRepository,
            $customerRegistrationFactory;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistrationFactory $customerRegistrationFactory
    ) {
        $this->customerRepository          = $customerRepository;
        $this->customerRegistrationFactory = $customerRegistrationFactory;
    }

    public function execute(CommandInterface $command)
    {
        $registeredCustomer = $this
            ->customerRegistrationFactory
            ->make(
                $command->getCustomerId(),
                $command->getFirstName(),
                $command->getLastNames(),
                $command->getUsername(),
                $command->getEmailAddress()
            );

        $this
            ->customerRepository
            ->create($registeredCustomer);
    }
}