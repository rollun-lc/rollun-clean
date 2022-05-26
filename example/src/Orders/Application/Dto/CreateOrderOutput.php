<?php

namespace example\Orders\Application\Dto;

class CreateOrderOutput
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $customerId;

    /**
     * @var string
     */
    public $marketplaceName;

    /**
     * @var string
     */
    public $status;

    /**
     * @var float
     */
    public $totalSum;

    /**
     * @var \DateTimeInterface
     */
    public $createdAt;
}