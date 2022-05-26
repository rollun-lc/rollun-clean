<?php

namespace example\Orders\Application\Interfaces;

use example\Orders\Application\Dto\CreateOrderInput;
use example\Orders\Application\Dto\CreateOrderOutput;

interface CreateOrderInterface
{
    public function createOrder(CreateOrderInput $orderDto): CreateOrderOutput;
}