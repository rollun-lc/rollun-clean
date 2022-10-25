<?php

namespace functional\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Frameworks\Factories\SymfonyMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleSymfonyMapper;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class SimpleSymfonyMapperTest extends TestCase
{
    public function testDtoToArray()
    {
        $dto = $this->makeDto();

        $mapper = $this->getMapper();

        $result = $mapper->mapToArray($dto);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('lastName', $result);
    }

    public function testDtoFromArray()
    {
        $array = $this->makeArray();

        $mapper = $this->getMapper();

        $result = $mapper->mapFromArray($array, get_class($this->makeDto()));

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('price', $result);
        $this->assertObjectHasAttribute('lastName', $result);
    }

    public function testDtoToArrayCamelCase()
    {
        $dto = $this->makeDto();

        $mapper = $this->getMapper([
            SymfonyMapperAbstractFactory::class => [
                SimpleSymfonyMapper::class => [
                    SymfonyMapperAbstractFactory::KEY_NAME_CONVERTER => CamelCaseToSnakeCaseNameConverter::class,
                ]
            ]
        ]);

        $result = $mapper->mapToArray($dto);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('last_name', $result);
    }

    public function testDtoFromArrayCamelCase()
    {
        $array = $this->makeArray();

        $mapper = $this->getMapper();

        $result = $mapper->mapFromArray($array, get_class($this->makeDto()));

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('price', $result);
        $this->assertObjectHasAttribute('lastName', $result);
    }

    public function testMapObjectToArray()
    {
        $dto = $this->makeDto();

        $mapper = $this->getMapper();

        $result = $mapper->map($dto, MapperInterface::TYPE_ARRAY);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('lastName', $result);
    }

    public function testMapArrayToObject()
    {
        $array = $this->makeArray();

        $mapper = $this->getMapper();

        $result = $mapper->map($array, get_class($this->makeDto()));

        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('price', $result);
        $this->assertObjectHasAttribute('lastName', $result);
    }

    public function testMapObjectToObject()
    {
        $dto = $this->makeDto();

        $mapper = $this->getMapper();

        $result = $mapper->map($dto, get_class($this->makeEntity()));

        $this->assertIsObject($result);
        $this->assertInstanceOf(get_class($this->makeEntity()), $result);
        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('name', $result);
        $this->assertObjectHasAttribute('price', $result);
        $this->assertObjectHasAttribute('lastName', $result);
    }

    /**
     * @return MapperInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getMapper($config = [])
    {
        global $container;

        $serviceConfig = $container->get('config');

        $config = array_merge_recursive($serviceConfig, $config);

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', $config);
        $serviceManager->configure($config['dependencies']);

        return $serviceManager->get(SimpleSymfonyMapper::class);
    }

    protected function makeArray()
    {
        return [
            'id' => 1,
            'name' => 'Hello',
            'last_name' => 'World',
            'price' => 1.1,
        ];
    }

    protected function makeDto()
    {
        $dto = new class() extends \stdClass {
            public $id;
            public $name;
            public $lastName;
            public $price;
        };
        foreach ($this->makeArray() as $key => $value) {
            $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $dto->{$property} = $value;
        }

        return $dto;
    }

    protected function makeEntity()
    {
        $object = new class() implements \Stringable {
            protected $id;
            protected $name;
            protected $lastName;
            protected $price;
            public function setId(int $id)
            {
                $this->id = $id;
            }
            public function setName(string $name)
            {
                $this->name = $name;
            }
            public function setPrice(float $price)
            {
                $this->price = $price;
            }
            public function setLastName(string $lastName)
            {
                $this->lastName = $lastName;
            }
            public function __toString()
            {
                return $this->name;
            }
        };

        foreach ($this->makeArray() as $key => $value) {
            $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $method = 'set' . $property;
            $object->{$method}($value);
        }

        return $object;
    }
}