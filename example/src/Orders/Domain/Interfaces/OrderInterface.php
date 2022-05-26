<?php

namespace example\Orders\Domain\Interfaces;

interface OrderInterface
{
    public function addOrderItem(OrderItemInterface $product): void;
}