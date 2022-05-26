<?php

namespace example\Orders\Frameworks;

use example\Orders\Application\Interfaces\CreateOrderInterface;
use example\Orders\Application\Interfaces\CustomerServiceInterface;
use example\Orders\Application\UseCases\CreateOrderUseCase;
use example\Orders\Application\Interfaces\MarketplaceRegistryInterface;
use example\Orders\Application\Interfaces\OrderRepositoryInterface;
use example\Orders\Infrastructure\Adapters\CustomerService;
use example\Orders\Infrastructure\Controllers\CreateOrderController;
use example\Orders\Infrastructure\Repositories\DataStoreOrderRepository;
use example\Orders\Application\Services\MarketplaceRegistry;
use example\Orders\Domain\Services\OrderCreator;
use rollun\datastore\DataStore\Factory\DataStoreAbstractFactory;
use rollun\datastore\DataStore\Factory\MemoryAbstractFactory;
use rollun\datastore\DataStore\Memory;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'abstract_factories' => [
                    AbstractServiceAbstractFactory::class,
                ]
            ],
            AbstractServiceAbstractFactory::KEY => [
                //CONTROLLERS
                'CreateOrderCallback' => [
                    AbstractServiceAbstractFactory::KEY_CLASS => CreateOrderController::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                        'useCase' => CreateOrderInterface::class,
                    ],
                ],

                // USE CASES
                CreateOrderInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => CreateOrderUseCase::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                        'orderRepository' => OrderRepositoryInterface::class,
                        'customerService' => CustomerServiceInterface::class,
                        'marketplaceRegistry' => MarketplaceRegistryInterface::class,
                        'orderFactory' => OrderCreator::class,
                    ],
                ],

                // REPOSITORIES
                // Здесь только разрешаются зависимости хранилища из уровня ниже
                OrderRepositoryInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => DataStoreOrderRepository::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                        'dataStore' => 'OrderMemoryDataStore',
                    ],
                ],

                // ADAPTERS
                // Здесь только разрешаются зависимости из других пакетов
                CustomerServiceInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => CustomerService::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                        'callback' => 'GetCustomerCallback'
                    ],
                ],

                // SERVICES
                MarketplaceRegistryInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => MarketplaceRegistry::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [],
                ],
                OrderCreator::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => OrderCreator::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [],
                ],
            ],
            DataStoreAbstractFactory::KEY_DATASTORE => [
                'OrderMemoryDataStore' => [
                    MemoryAbstractFactory::KEY_CLASS => Memory::class,
                ],
            ],
        ];
    }
}