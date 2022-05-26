<?php

namespace example\Orders\Application\Interfaces;

use example\Orders\Domain\Entities\Customer;

interface CustomerServiceInterface
{
    public function getCustomer(int $id): Customer;
}