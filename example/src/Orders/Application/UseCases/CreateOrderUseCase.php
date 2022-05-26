<?php

namespace example\Orders\Application\UseCases;

use Clean\Common\Utils\Extensions\Collection;
use example\Orders\Application\Dto\CreateOrderInput;
use example\Orders\Application\Dto\CreateOrderOutput;
use example\Orders\Application\Dto\OrderItemDto;
use example\Orders\Application\Interfaces\CreateOrderInterface;
use example\Orders\Application\Interfaces\CustomerServiceInterface;
use example\Orders\Application\Interfaces\MarketplaceRegistryInterface;
use example\Orders\Application\Interfaces\OrderRepositoryInterface;
use example\Orders\Domain\Services\OrderCreator;

class CreateOrderUseCase implements CreateOrderInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerServiceInterface
     */
    protected $customerService;

    /**
     * @var MarketplaceRegistryInterface
     */
    protected $marketplaceRegistry;

    /**
     * @var OrderCreator
     */
    protected $orderFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerServiceInterface $customerService,
        MarketplaceRegistryInterface $marketplaceRegistry,
        OrderCreator $orderFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->customerService = $customerService;
        $this->marketplaceRegistry = $marketplaceRegistry;
        $this->orderFactory = $orderFactory;
    }

    public function createOrder(CreateOrderInput $request): CreateOrderOutput
    {
        $marketplace = $this->marketplaceRegistry->getByName($request->marketplaceName);
        $customer = $this->customerService->getCustomer($request->customerId);
        $order = $this->orderFactory->createOrder(
            $marketplace,
            $customer,
            new Collection(
                array_map(function(OrderItemDto $dto){
                    return (array) $dto;
                },
                $request->items)
            )
        );
        $this->orderRepository->insert($order);

        // TODO Add mapper
        $response = new CreateOrderOutput();
        $response->id = $order->getId();
        $response->marketplaceName = $order->getMarketplaceName();
        $response->customerId = $order->getCustomerId();
        $response->status = $order->getStatus();
        $response->totalSum = $order->calculateTotalSum();
        $response->createdAt = (string) $order->getCreatedAt();

        return $response;
    }
}