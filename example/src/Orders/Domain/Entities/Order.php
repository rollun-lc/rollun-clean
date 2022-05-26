<?php

namespace example\Orders\Domain\Entities;

use example\Orders\Domain\Interfaces\OrderInterface;
use example\Orders\Domain\Interfaces\OrderItemInterface;

class Order implements OrderInterface
{
    public const STATUS_PENDING = 'new';

    public const STATUS_PAID = 'paid';

    public const STATUS_DONE = 'done';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $marketplaceName;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var OrderItemInterface[]|\Traversable
     */
    protected $orderItems;

    /**
     * @var float
     */
    protected $totalSum;

    /**
     * @var string
     */
    protected $status = self::STATUS_PENDING;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface
     */
    protected $updatedDate;

    /**
     * @param string $id
     * @param string $marketplaceName
     * @param string $customerId
     * @param OrderItemInterface[]|\Traversable $orderItems
     */
    public function __construct(string $marketplaceName, string $customerId, $orderItems = [])
    {
        $this->marketplaceName = $marketplaceName;
        $this->customerId = $customerId;
        $this->orderItems = $orderItems;
    }

    /**
     * @param OrderItemInterface $product
     * @return void
     */
    public function addOrderItem(OrderItemInterface $product): void
    {
        $this->orderItems[] = $product;
        $this->totalSum = $this->calculateTotalSum();
    }

    /**
     * @return float
     */
    public function calculateTotalSum(): float
    {
        $totalSum = 0.0;
        foreach ($this->orderItems as $orderItem) {
            $totalSum += $orderItem->getQuantity() * $orderItem->getPrice();
        }
        return $totalSum;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMarketplaceName(): string
    {
        return $this->marketplaceName;
    }

    /**
     * @param string $marketplaceName
     */
    public function setMarketplaceName(string $marketplaceName): void
    {
        $this->marketplaceName = $marketplaceName;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return OrderItemInterface[]|\Traversable
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItemInterface[]|\Traversable $orderItems
     */
    public function setOrderItems($orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedDate(): \DateTimeInterface
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTimeInterface $updatedDate
     */
    public function setUpdatedDate(\DateTimeInterface $updatedDate): void
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}