<?php

namespace example\Orders\Domain\Entities;

use example\Orders\Domain\Interfaces\OrderItemInterface;

class OrderItem implements OrderItemInterface
{
    /**
     * @var string
     */
    protected $rid;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $price;

    public function __construct(string $rid, float $price, int $quantity)
    {
        $this->rid = $rid;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getRid(): string
    {
        return $this->rid;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}