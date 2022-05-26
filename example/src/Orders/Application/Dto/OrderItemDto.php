<?php

namespace example\Orders\Application\Dto;

class OrderItemDto
{
    /**
     * @var string
     */
    public $rid;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var float
     */
    public $price;
}