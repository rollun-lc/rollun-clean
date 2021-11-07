<?php

namespace unit\Utils\Extensions;

use Clean\Common\Domain\Interfaces\ArrayableInterface;
use Clean\Common\Utils\Extensions\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected function createSimpleInstance($id, $data = [])
    {
        $instance = new \stdClass();
        $instance->id = $id;
        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }
        return $instance;
    }

    public function testMapWithKeyObject()
    {
        $collection = new Collection([
            $this->createSimpleInstance(1)
        ]);

        $result = $collection->mapWithKey('id');

        $this->assertArrayHasKey(1, $result);
        $this->assertInstanceOf(\stdClass::class, $result->offsetGet(1));
    }

    public function testMapWithKeyArray()
    {
        $collection = new Collection([
            ['id' => 1]
        ]);

        $result = $collection->mapWithKey('id');

        $this->assertArrayHasKey(1, $result);
        $this->assertEquals(['id' => 1], $result->offsetGet(1));
    }

    public function testToArrayArrayable()
    {
        $object = new class() implements ArrayableInterface {
            public $id = 1;
            public function toArray(): array
            {
                return ['id' => $this->id];
            }
        };
        $collection = new Collection([
            $object
        ]);

        $result = $collection->toArray();

        $this->assertEquals([['id' => 1]], $result);
    }

    public function testToArrayNotArrayable()
    {
        $collection = new Collection([
            $this->createSimpleInstance(1)
        ]);

        $result = $collection->toArray();

        $this->assertEquals([['id' => 1]], $result);
    }

    public function testMap()
    {
        $collection = new Collection([
            $this->createSimpleInstance(1)
        ]);

        $result = $collection->map(function ($item) {
            $instance = new \stdClass();
            $instance->id = $item->id;
            return $instance;
        });

        $this->assertInstanceOf(\stdClass::class, $result[0]);
    }

    public function testFilter()
    {
        $collection = new Collection([
            $this->createSimpleInstance(1),
            $this->createSimpleInstance(2),
            $this->createSimpleInstance(3),
        ]);

        $result = $collection->filter(function ($item) {
            return $item->id == 2;
        });

        $this->assertCount(1, $result);;
    }

    public function testMerge()
    {
        $collection = new Collection([
            $this->createSimpleInstance(1)
        ]);

        $result = $collection->merge(
            new Collection([
                $this->createSimpleInstance(2)
            ])
        );

        $this->assertCount(2, $result);
    }
}