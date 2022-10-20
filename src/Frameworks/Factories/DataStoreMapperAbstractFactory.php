<?php

namespace Clean\Common\Frameworks\Factories;

use Clean\Common\Infrastructure\Mappers\DataStoreMapperAbstract;
use Clean\Common\Infrastructure\Mappers\SimpleSymfonyMapper;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;

class DataStoreMapperAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    public const KEY_CLASS = 'class';

    public const KEY_MAPPER = 'mapper';

    public const KEY_ENTITY_CLASS = 'entityClass';

    protected const DEFAULT_CLASS = DataStoreMapperAbstract::class;

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? [];
        $className = $config[self::KEY_CLASS] ?? null;

        return $className && is_a($className, self::DEFAULT_CLASS, true);
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')[self::KEY][$requestedName] ?? [];
        $mapperClass = $config[self::KEY_MAPPER] ?? SimpleSymfonyMapper::class;
        $mapper = $container->get($mapperClass);
        $entityClass = $config[self::KEY_ENTITY_CLASS] ?? null;

        $className = $config[self::KEY_CLASS];

        return new $className($mapper, $entityClass);
    }
}