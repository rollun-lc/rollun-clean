<?php

namespace unit\Infrastructure\Mappers;

use Clean\Common\Frameworks\Factories\DataStoreMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleDataStoreMapper;
use Clean\Common\Utils\Extensions\DateTime;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class SimpleDataStoreMapperTest extends TestCase
{
    public function testToStore()
    {
        $mapper = $this->makeMapper();
        $result = $mapper->fromStore($this->getTestArray());

        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('datetime', $result);

        $this->assertInstanceOf(\DateTimeInterface::class, $result->getDateTime());

        $array = $mapper->toStore($result);
    }

    protected function makeMapper()
    {
        global $container;

        $config = $container->get('config');
        $config[DataStoreMapperAbstractFactory::KEY]['SimpleMapper'] = [
            DataStoreMapperAbstractFactory::KEY_CLASS => SimpleDataStoreMapper::class,
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
            'name' => 'Hello',
            'datetime' => '2022-02-02 02:02:02'
        ];
    }

    protected function getTestObject()
    {
        $array = $this->getTestArray();
        $array['datetime'] = new DateTime($array['datetime']);
        return new class(...$array) extends \stdClass {
            protected $id;
            protected $name;
            protected $datetime;
            public function __construct(int $id, string $name, \DateTimeInterface $datetime)
            {
                $this->id = $id;
                $this->name = $name;
                $this->datetime = $datetime;
            }
            public function getId(): int
            {
                return $this->id;
            }
            public function setId(int $id): void
            {
                $this->id = $id;
            }
            public function getName(): string
            {
                return $this->name;
            }
            public function setName(string $name): void
            {
                $this->name = $name;
            }
            public function getDatetime(): \DateTimeInterface
            {
                return $this->datetime;
            }
            public function setDatetime(\DateTimeInterface $datetime): void
            {
                $this->datetime = $datetime;
            }

        };
    }
}