<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Extensions\DateTime;
use Clean\Common\Utils\Extensions\TypedCollection\NotValidTypeException;
use Clean\Common\Utils\Extensions\TypedCollection\TypedCollection;
use Clean\Common\Utils\Extensions\TypedCollection\TypedCollectionAbstract;
use PHPUnit\Framework\TestCase;

class TypedCollectionTest extends TestCase
{
    public function testObjectValid()
    {
        $collection = new TypedCollection(\stdClass::class);

        $collection[] = new \stdClass();
        $collection->append(new \stdClass());
        $collection->offsetSet(3, new \stdClass());

        $this->assertCount(3, $collection);
    }

    public function testObjectFailed()
    {
        $collection = new TypedCollection(\stdClass::class);

        $this->expectException(NotValidTypeException::class);

        $collection[] = new DateTime();

        $this->assertCount(3, $collection);
    }

    public function testScalarValid()
    {
        $collection = new TypedCollection(TypedCollection::TYPE_INTEGER);

        $collection[] = 1;
        $collection->append(2);
        $collection->offsetSet(3, 3);

        $this->assertCount(3, $collection);
    }

    public function testSalarFailed()
    {
        $collection = new TypedCollection(TypedCollection::TYPE_INTEGER, [1, 2, 3]);

        $this->expectException(NotValidTypeException::class);

        $collection[] = true;

        $this->assertCount(3, $collection);
    }

    public function testMap()
    {
        $collection = new TypedCollection(\stdClass::class, $this->getObjects());

        $result = $collection->map(
            function ($item) {
                return $item->id;
            },
            TypedCollection::TYPE_INTEGER
        );

        $this->assertInstanceOf(TypedCollection::class, $result);
    }

    public function testMapWithKey()
    {
        $collection = new TypedCollection(\stdClass::class, $this->getObjects());

        $result = $collection->mapWithKey('id', null, \stdClass::class);

        $this->assertInstanceOf(TypedCollection::class, $result);
    }

    public function testMapWithoutClass()
    {
        $collection = new TypedCollection(\stdClass::class, $this->getObjects());

        $result = $collection->map(
            function ($item) {
                return $item->id;
            }
        );

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapWithKeyWithoutClass()
    {
        $collection = new TypedCollection(\stdClass::class, $this->getObjects());

        $result = $collection->mapWithKey('id');

        $this->assertInstanceOf(Collection::class, $result);
    }

    protected function getObjects($class = \stdClass::class)
    {
        $results = [];
        for ($i = 1; $i <= 3; $i++) {
            $item = new $class();
            $item->id = $i;
            $item->name = 'Name ' . $i;
            $results[] = $item;
        }

        return $results;
    }

    public function testMerge()
    {
        $collection = new TypedCollection(\stdClass::class);

        $newCollection = new TypedCollection(\stdClass::class, $this->getObjects());

        $merged = $collection->merge($newCollection);

        $this->assertInstanceOf(TypedCollectionAbstract::class, $merged);
    }

    public function testFilter()
    {
        $collection = new TypedCollection(\stdClass::class, $this->getObjects());

        $filtered = $collection->filter(function (\stdClass $item) {
            return $item->id == 1;
        });

        $this->assertCount(1, $filtered);
    }
}