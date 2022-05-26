<?php

namespace example\Customers\Frameworks;

use example\Customers\Application\Interfaces\GetCustomerInterface;
use example\Customers\Application\UseCases\GetCustomerUseCase;
use example\Customers\Infrastructure\Controllers\GetCustomerController;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            AbstractServiceAbstractFactory::KEY => [
                //CONTROLLERS
                'GetCustomerCallback' => [
                    AbstractServiceAbstractFactory::KEY_CLASS => GetCustomerController::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [
                        'useCase' => GetCustomerInterface::class,
                    ],
                ],

                // USE CASES
                GetCustomerInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => GetCustomerUseCase::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => [

                    ],
                ],

                // REPOSITORIES

                // SERVICES

            ],
        ];
    }
}