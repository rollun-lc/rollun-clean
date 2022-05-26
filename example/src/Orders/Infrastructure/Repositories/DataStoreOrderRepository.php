<?php

namespace example\Orders\Infrastructure\Repositories;

use Clean\Common\Utils\Extensions\DateTime;
use example\Orders\Application\Interfaces\OrderRepositoryInterface;
use example\Orders\Domain\Entities\Order;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;

class DataStoreOrderRepository implements OrderRepositoryInterface
{
    protected $dataStore;

    public function __construct(DataStoreInterface $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    public function insert(Order $order)
    {
        $currentDate = new DateTime();
        $response = $this->dataStore->create([
            'id' => $order->getId(),
            'marketplace_name' => $order->getMarketplaceName(),
            'customer_id' => $order->getCustomerId(),
            'status' => $order->getStatus(),
            'created_at' => (string) $currentDate,
            'updated_at' => (string) $currentDate,
        ]);
        if (!$response) {
            throw new \Exception('Can not save order');
        }

        $order->setCreatedAt($currentDate);
        $order->setUpdatedDate($currentDate);
    }
}