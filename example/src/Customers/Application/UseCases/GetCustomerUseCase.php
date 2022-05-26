<?php

namespace example\Customers\Application\UseCases;

use example\Customers\Application\Dto\GetCustomerOutput;
use example\Customers\Application\Interfaces\GetCustomerInterface;
use example\Customers\Domain\Entities\Customer;

class GetCustomerUseCase implements GetCustomerInterface
{
    public function getCustomer($id)
    {
        // TODO Get from repository
        $customer = new Customer($id, 'john.doe@example.com', 'John Doe');

        // TODO Add mapper
        $output = new GetCustomerOutput();
        $output->id = $customer->getId();
        $output->name = $customer->getName();
        $output->email = $customer->getEmail();

        return $output;
    }
}