<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\DateTime;
use PHPUnit\Framework\TestCase;
use Clean\Common\Utils\Extensions\TypedCollection\NotValidTypeException;
use Clean\Common\Utils\Extensions\TypedCollection\TypedCollection;

class TypedCollectionTest extends TestCase
{
    public function testObjectValid()
    {
        $collection = new class() extends TypedCollection {
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
        $collection = new class() extends TypedCollection {
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
        $collection = new class() extends TypedCollection {
            protected function getType()
            {
                return TypedCollection::TYPE_INTEGER;
            }
        };

        $collection[] = 1;
        $collection->append(2);
        $collection->offsetSet(3, 3);

        $this->assertCount(3, $collection);
    }

    public function testSalarFailed()
    {
        $collection = new class() extends TypedCollection {
            protected function getType()
            {
                return TypedCollection::TYPE_INTEGER;
            }
        };

        $this->expectException(NotValidTypeException::class);

        $collection[] = true;

        $this->assertCount(3, $collection);
    }
}