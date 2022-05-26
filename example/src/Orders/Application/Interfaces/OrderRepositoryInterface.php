<?php

namespace example\Orders\Application\Interfaces;

use example\Orders\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function insert(Order $order);
}