<?php

namespace example\Orders\Infrastructure\Adapters;

use example\Orders\Application\Interfaces\CustomerServiceInterface;
use example\Orders\Domain\Entities\Customer;

class CustomerService implements CustomerServiceInterface
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function getCustomer(int $id): Customer
    {
        $response = json_decode(call_user_func($this->callback, ['id' => $id]));
        if ($response->code !== 200) {
            throw new \Exception('Can not get customer');
        }

        $customer = new Customer();
        $customer->setId($response->data->id);
        return $customer;
    }
}