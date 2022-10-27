<?php

namespace functional\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\DataStoreMapperInterface;
use Clean\Common\Frameworks\Factories\DataStoreMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleDataStoreMapper;
use Clean\Common\Utils\Extensions\DateTime;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class SimpleDataStoreMapperTest extends TestCase
{
    public function testFromStore()
    {
        $array = $this->getTestArray();
        $mapper = $this->makeMapper();
        $result = $mapper->fromStore($array);

        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('firstName', $result);
        $this->assertObjectHasAttribute('createdAt', $result);

        $this->assertInstanceOf(\DateTimeInterface::class, $result->getCreatedAt());
    }

    public function testToStore()
    {
        $object = $this->getTestObject();
        $mapper = $this->makeMapper();
        $result = $mapper->toStore($object);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('created_at', $result);

        $this->assertEquals(new DateTime('2022-02-02 02:02:02'), new DateTime($result['created_at']));
    }

    protected function makeMapper(): DataStoreMapperInterface
    {
        global $container;

        $config = $container->get('config');
        $config[DataStoreMapperAbstractFactory::KEY]['SimpleMapper'] = [
            DataStoreMapperAbstractFactory::KEY_CLASS => SimpleDataStoreMapper::class,
            DataStoreMapperAbstractFactory::KEY_MAPPER => 'DataStoreSymfonyMapper',
            DataStoreMapperAbstractFactory::KEY_ENTITY_CLASS => get_class($this->getTestObject()),
        ];

        $manager = new ServiceManager();
        $manager->setService('config', $config);
        $manager->configure($config['dependencies']);

        return $manager->get('SimpleMapper');
    }

    protected function getTestArray()
    {
        return [
            'id' => 1,
            'first_name' => 'Hello',
            'created_at' => '2022-02-02 02:02:02'
        ];
    }

    protected function getTestObject()
    {
        $array = $this->getTestArray();
        $array['created_at'] = new DateTime($array['created_at']);
        $object = new class() extends \stdClass {
            protected $id;
            protected $firstName;
            protected $createdAt;
            public function getId(): int
            {
                return $this->id;
            }
            public function setId(int $id): void
            {
                $this->id = $id;
            }
            public function getFirstName(): string
            {
                return $this->firstName;
            }
            public function setFirstName(string $firstName): void
            {
                $this->firstName = $firstName;
            }
            public function getCreatedAt(): \DateTimeInterface
            {
                return $this->createdAt;
            }
            public function setCreatedAt(\DateTimeInterface $createdAt): void
            {
                $this->createdAt = $createdAt;
            }
        };
        foreach ($array as $key => $value) {
            $setter = 'set' . lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            if (method_exists($object, $setter)) {
                $object->{$setter}($value);
            }
        }
        return $object;
    }
}