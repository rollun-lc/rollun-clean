<?php

namespace Clean\Common\Frameworks;

use Clean\Common\Application\Interfaces\DtoMapperInterface;
use Clean\Common\Application\Services\DtoMapper;
use rollun\utils\Factory\AbstractServiceAbstractFactory;

/**
 * @todo Возможно конфиги фреймворка не нужны в этом пакете
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            AbstractServiceAbstractFactory::KEY => [
                DtoMapperInterface::class => [
                    AbstractServiceAbstractFactory::KEY_CLASS => DtoMapper::class,
                    AbstractServiceAbstractFactory::KEY_DEPENDENCIES => []
                ],
            ]
        ];
    }
}