<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Extensions\DateTime;
use PHPUnit\Framework\TestCase;
use Clean\Common\Utils\Extensions\TypedCollection\NotValidTypeException;
use Clean\Common\Utils\Extensions\TypedCollection\TypedCollectionAbstract;

class TypedCollectionAbstractTest extends TestCase
{
    public function testObjectValid()
    {
        $collection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $collection[] = new \stdClass();
        $collection->append(new \stdClass());
        $collection->offsetSet(3, new \stdClass());

        $this->assertCount(3, $collection);
    }

    public function testObjectFailed()
    {
        $collection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $this->expectException(NotValidTypeException::class);

        $collection[] = new DateTime();

        $this->assertCount(3, $collection);
    }

    public function testScalarValid()
    {
        $collection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return TypedCollectionAbstract::TYPE_INTEGER;
            }
        };

        $collection[] = 1;
        $collection->append(2);
        $collection->offsetSet(3, 3);

        $this->assertCount(3, $collection);
    }

    public function testSalarFailed()
    {
        $collection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return TypedCollectionAbstract::TYPE_INTEGER;
            }
        };

        $this->expectException(NotValidTypeException::class);

        $collection[] = true;

        $this->assertCount(3, $collection);
    }

    public function testMap()
    {
        $collection = new class($this->getObjects()) extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $result = $collection->map(function ($item) {
            return $item->id;
        }, Collection::class);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapWithKey()
    {
        $collection = new class($this->getObjects()) extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $result = $collection->mapWithKey('id', null, Collection::class);

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function testMapToIntergerTypedCollection()
    {
        $collection = new class($this->getObjects()) extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $newCollection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return TypedCollectionAbstract::TYPE_INTEGER;
            }
        };

        $result = $collection->mapTo(get_class($newCollection), function ($item) {
            return $item->id;
        }, Collection::class);

        $this->assertInstanceOf(get_class($newCollection), $result);
    }

    public function testMapWithKeyToStringTypedCollection()
    {
        $collection = new class($this->getObjects()) extends TypedCollectionAbstract {
            protected function getType()
            {
                return \stdClass::class;
            }
        };

        $newCollection = new class() extends TypedCollectionAbstract {
            protected function getType()
            {
                return TypedCollectionAbstract::TYPE_STRING;
            }
        };

        $result = $collection->mapWithKeyTo(get_class($newCollection), 'id', function ($item) {
            return $item->name;
        });

        $this->assertInstanceOf(get_class($newCollection), $result);
    }

    protected function getObjects()
    {
        $results = [];
        for ($i = 1; $i <= 3; $i++) {
            $item = new \stdClass();
            $item->id = $i;
            $item->name = 'Name ' . $i;
            $results[] = $item;
        }

        return $results;
    }
}