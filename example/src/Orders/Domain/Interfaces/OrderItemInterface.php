<?php

namespace example\Orders\Domain\Interfaces;

interface OrderItemInterface
{
    public function getQuantity(): int;

    public function getPrice(): float;
}