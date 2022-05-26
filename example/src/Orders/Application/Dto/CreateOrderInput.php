<?php

namespace example\Orders\Application\Dto;

class CreateOrderInput
{
    /**
     * @var string
     */
    public $customerId;

    /**
     * @var string
     */
    public $marketplaceName;

    /**
     * @var OrderItemDto[]
     */
    public $items;
}