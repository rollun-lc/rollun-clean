<?php

namespace functional\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Frameworks\Factories\SymfonyMapperAbstractFactory;
use Clean\Common\Infrastructure\Mappers\SimpleSymfonyMapper;
use Clean\Common\Utils\Extensions\DateTime;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Test\OpenAPI\V1\DTO\Inner;
use Test\OpenAPI\V1\DTO\Response;
use Test\OpenAPI\V1\DTO\Test;

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

        $mapper = $this->getMapper(
            SimpleSymfonyMapper::class,
            [
                SymfonyMapperAbstractFactory::class => [
                    SimpleSymfonyMapper::class => [
                        SymfonyMapperAbstractFactory::KEY_NAME_CONVERTER => CamelCaseToSnakeCaseNameConverter::class,
                    ]
                ]
            ]
        );

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

    public function testObjectsWithoutSetter()
    {
        $mapper = $this->getMapper();

        $array = [
            'id' => 1,
            'name' => 'Hello',
            'something' => 'anything'
        ];

        $entity = new class() extends \stdClass {
            public $id;
            public $name;
        };

        $result = $mapper->map($array, get_class($entity));

        $this->assertIsObject($result);
        $this->assertObjectNotHasAttribute('something', $result);
    }

    /**
     * TODO
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testObjectsWithMagicSetter()
    {
        $mapper = $this->getMapper();

        $array = [
            'id' => 1,
            'name' => 'Hello',
            'something' => 'anything'
        ];

        $entity = new class() extends \stdClass {
            private $id;
            private $name;
            public function __set(string $name, $value): void
            {
                if (!property_exists($this, $name)) {
                    throw new \Exception('Unknown property ' . $name);
                }

                $this->{$name} = $value;
            }
        };

        $this->expectErrorMessage('Unknown property something');

        $result = $mapper->map($array, get_class($entity));

        $this->assertObjectNotHasAttribute('something', $result);
    }

    public function testMapIgnoringMagicSetter()
    {
        $mapper = $this->getMapper('OpenApiSimpleSymfonyMapper');

        $array = [
            'id' => 1,
            'name' => 'Hello',
            'something' => 'anything'
        ];

        $entity = new class() extends \stdClass {
            private $id;
            private $name;
            public function __set(string $name, $value): void
            {
                if (!property_exists($this, $name)) {
                    throw new \Exception('Unknown property ' . $name);
                }

                $this->{$name} = $value;
            }
        };

        $result = $mapper->map($array, get_class($entity));

        $this->assertObjectNotHasAttribute('something', $result);
    }

    /**
     * @return MapperInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getMapper($mapper = SimpleSymfonyMapper::class, $config = [])
    {
        global $container;

        $serviceConfig = $container->get('config');

        $config = array_merge_recursive($serviceConfig, $config);

        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', $config);
        $serviceManager->configure($config['dependencies']);

        return $serviceManager->get($mapper);
    }

    protected function makeArray()
    {
        return [
            'id' => 1,
            'name' => 'Hello',
            'last_name' => 'World',
            'price' => 1.1,
            'datetime' => '2022-02-02 02:02:02'
        ];
    }

    protected function makeDto()
    {
        $dto = new class() extends \stdClass {
            public $id;
            public $name;
            public $lastName;
            public $price;
            public $datetime;
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
            protected $datetime;
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
            public function setDatetime(\DateTimeInterface $dateTime)
            {
                $this->datetime = $dateTime;
            }
            public function __toString()
            {
                return $this->name;
            }
        };

        foreach ($this->makeArray() as $key => $value) {
            $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $method = 'set' . $property;
            if ($property === 'datetime') {
                $value = new DateTime($value);
            }
            $object->{$method}($value);
        }

        return $object;
    }
}