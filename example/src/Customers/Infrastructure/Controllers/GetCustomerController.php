<?php

namespace example\Customers\Infrastructure\Controllers;

use example\Customers\Application\Interfaces\GetCustomerInterface;

class GetCustomerController
{
    protected $useCase;

    public function __construct(GetCustomerInterface $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke($data)
    {
        $id = $data['id'];
        if (empty($id)) {
            throw new \Exception('Id is require');
        }
        $output = $this->useCase->getCustomer($id);

        return json_encode([
            'code' => 200,
            'data' => (array) $output
        ]);
    }
}