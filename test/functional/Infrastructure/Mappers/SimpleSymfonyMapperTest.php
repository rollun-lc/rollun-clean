<?php

namespace functional\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Infrastructure\Mappers\SimpleSymfonyMapper;
use PHPUnit\Framework\TestCase;

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
    }

    /**
     * @return MapperInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getMapper()
    {
        global $container;

        return $container->get(SimpleSymfonyMapper::class);
    }

    protected function makeArray()
    {
        return [
            'id' => 1,
            'name' => 'Hello',
            'price' => 1.1
        ];
    }

    protected function makeDto()
    {
        $dto = new class() extends \stdClass {
            public $id;
            public $name;
            public $price;
        };
        foreach ($this->makeArray() as $key => $value) {
            $dto->{$key} = $value;
        }

        return $dto;
    }

    protected function makeEntity()
    {
        $object = new class() implements \Stringable {
            protected $id;
            protected $name;
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
            public function __toString()
            {
                return $this->name;
            }
        };

        foreach ($this->makeArray() as $key => $value) {
            $method = 'set' . $key;
            $object->{$method}($value);
        }

        return $object;
    }
}