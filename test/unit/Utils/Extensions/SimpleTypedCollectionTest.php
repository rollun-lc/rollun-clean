<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\DateTime;
use Clean\Common\Utils\Extensions\TypedCollection\NotValidTypeException;
use Clean\Common\Utils\Extensions\TypedCollection\SimpleTypedCollection;
use PHPUnit\Framework\TestCase;

class SimpleTypedCollectionTest extends TestCase
{
    public function testObjectValid()
    {
        $collection = new SimpleTypedCollection(\stdClass::class);

        $collection[] = new \stdClass();
        $collection->append(new \stdClass());
        $collection->offsetSet(3, new \stdClass());

        $this->assertCount(3, $collection);
    }

    public function testObjectFailed()
    {
        $collection = new SimpleTypedCollection(\stdClass::class);

        $this->expectException(NotValidTypeException::class);

        $collection[] = new DateTime();

        $this->assertCount(3, $collection);
    }

    public function testScalarValid()
    {
        $collection = $collection = new SimpleTypedCollection(SimpleTypedCollection::TYPE_INTEGER);

        $collection[] = 1;
        $collection->append(2);
        $collection->offsetSet(3, 3);

        $this->assertCount(3, $collection);
    }

    public function testSalarFailed()
    {
        $collection = new SimpleTypedCollection(SimpleTypedCollection::TYPE_INTEGER);

        $this->expectException(NotValidTypeException::class);

        $collection[] = true;

        $this->assertCount(3, $collection);
    }
}