<?php

namespace Demo\Infrastructure\Bus\CommandHandler\Customer;

use Demo\Application\CommandHandlerInterface;
use Demo\Application\CommandInterface;
use Demo\Infrastructure\Repository\Customer\CustomerRepository;


/**
 * Class description
 *
 * @package Demo\Infrastructure\Bus\CommandHandler\Customer
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
final class UpdateCustomerEmailCommandHandler implements CommandHandlerInterface
{
    private $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    public function execute(CommandInterface $command)
    {
        $customer = $this
            ->customerRepository
            ->find($command->getCustomerId());

        $customer->updateEmailAddress($command->getEmailAddress());

        $this
            ->customerRepository
            ->update($customer);
    }
}