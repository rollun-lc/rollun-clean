<?php

namespace Clean\Common\Frameworks;

use Clean\Common\Application\Interfaces\EntityMapperInterface;
use Clean\Common\Frameworks\Factories\DataStoreMapperAbstractFactory;
use Clean\Common\Frameworks\Factories\SymfonyMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleDataStoreMapper;
use Clean\Common\Infrastructure\Mappers\SimpleSymfonyMapper;
use Clean\Common\Infrastructure\Services\SimpleMapper\SimpleMapper;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

/**
 * @todo Возможно конфиги фреймворка не нужны в этом пакете
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'abstract_factories' => [
                    SymfonyMapperAbstractFactory::class,
                    DataStoreMapperAbstractFactory::class,
                ]
            ],
            SymfonyMapperAbstractFactory::KEY => [
                SimpleSymfonyMapper::class => [
                    SymfonyMapperAbstractFactory::KEY_CLASS => SimpleSymfonyMapper::class,
                    SymfonyMapperAbstractFactory::KEY_DEPENDENCIES => []
                ]
            ],
            AbstractServiceAbstractFactory::KEY => [
                EntityMapperInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => SimpleMapper::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => []
                ],
            ]
        ];
    }
}