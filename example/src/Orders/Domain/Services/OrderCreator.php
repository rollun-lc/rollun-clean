<?php

namespace example\Orders\Domain\Services;

use Clean\Common\Utils\Extensions\Collection;
use example\Orders\Domain\Entities\Customer;
use example\Orders\Domain\Entities\Marketplace;
use example\Orders\Domain\Entities\Order;
use example\Orders\Domain\Entities\OrderItem;

class OrderCreator
{
    public function createOrder(
        Marketplace $marketplace,
        Customer $customer,
        Collection $items,
        string $id = null
    ) {
        $items = $items->map(function ($item) {
            if (is_array($item)) {
                $item = new OrderItem($item['rid'], $item['price'], $item['quantity']);
            }
            return $item;
        });
        $order = new Order($marketplace->getName(), $customer->getId(), $items);
        if (!$id) {
            $id = $this->generateId($order);
        }
        $order->setId($id);

        return $order;
    }

    protected function generateId(Order $order)
    {
        return md5($order->getMarketplaceName() . $order->getCustomerId() . implode(
            $order->getOrderItems()->map(function (OrderItem $item) {
                return $item->getRid() . $item->getPrice() . $item->getQuantity();
            })->toArray()
        ));
    }
}