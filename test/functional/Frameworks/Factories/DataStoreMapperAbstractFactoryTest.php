<?php

namespace functional\Frameworks\Factories;

use Clean\Common\Frameworks\Factories\DataStoreMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleDataStoreMapper;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class DataStoreMapperAbstractFactoryTest extends TestCase
{
    public function testMakeSimple()
    {
        global $container;

        $config = $container->get('config');
        $config[DataStoreMapperAbstractFactory::KEY]['SimpleDataStoreMapper'] = [
            DataStoreMapperAbstractFactory::KEY_CLASS => SimpleDataStoreMapper::class,
            DataStoreMapperAbstractFactory::KEY_MAPPER => 'DataStoreSymfonyMapper',
            DataStoreMapperAbstractFactory::KEY_ENTITY_CLASS => \stdClass::class,
        ];

        $manager = new ServiceManager();
        $manager->setService('config', $config);
        $manager->configure($config['dependencies']);

        $instance = $manager->get('SimpleDataStoreMapper');

        $this->assertInstanceOf(SimpleDataStoreMapper::class, $instance);
    }
}