<?php

namespace example\Orders\Infrastructure\Controllers;

use example\Orders\Application\Dto\CreateOrderInput;
use example\Orders\Application\Dto\OrderItemDto;
use example\Orders\Application\Interfaces\CreateOrderInterface;
use example\Orders\Application\UseCases\CreateOrderUseCase;

class CreateOrderController
{
    protected $useCase;

    public function __construct(CreateOrderInterface $useCase)
    {
        $this->useCase = $useCase;
    }

    public function __invoke($data)
    {
        try {
            $input = new CreateOrderInput();
            $input->customerId = $data['customer_id'];
            $input->marketplaceName = $data['marketplace_name'];

            if (empty($data['items'])) {
                throw new \Exception('Items is required');
            }

            foreach ($data['items'] as $item) {
                $orderItem = new OrderItemDto();
                $orderItem->price = $item['price'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->rid = $item['rid'];
                $input->items[] = $orderItem;
            }

            $output = $this->useCase->createOrder($input);
            return $this->output(200, $output);
        } catch (\Throwable $exception) {
            return $this->output($exception->getCode(), $exception->getMessage());
        }
    }

    protected function output(int $status, $output)
    {
        return json_encode([
            'status' => $status,
            'data' => (array) $output
        ]);
    }
}