<?php

namespace unit\Utils\Extensions\ArrayObject;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{
    public function testSerializeString()
    {
        $instance = new ArrayObject();

        $instance->addItem(new ArrayObjectItem('hello'));
        $instance->addItem(new ArrayObjectItem('world'));

        $json = json_encode($instance);

        $this->assertEquals('["hello","world"]', $json);
    }

    public function testSerializeIndexedArray()
    {
        $instance = new ArrayObject();

        $instance->addItem(new ArrayObjectItem(['hello', 'world']));
        $instance->addItem(new ArrayObjectItem(['world', 'hello']));

        $json = json_encode($instance);

        $this->assertEquals('[["hello","world"],["world","hello"]]', $json);
    }

    public function testSerializeAssociativeArray()
    {
        $instance = new ArrayObject();

        $array1 = ['id' => 1, 'name' => 'hello'];
        $array2 = ['id' => 2, 'name' => 'world'];

        $instance->addItem(new ArrayObjectItem($array1));
        $instance->addItem(new ArrayObjectItem($array2));

        $json = json_encode($instance);

        $this->assertEquals('[{"id":1,"name":"hello"},{"id":2,"name":"world"}]', $json);
    }

    public function testSerializeObject()
    {
        $instance = new ArrayObject();

        $object1 = new \stdClass();
        $object1->id = 1;
        $object1->name = 'hello';

        $object2 = new \stdClass();
        $object2->id = 2;
        $object2->name = 'world';

        $instance->addItem(new ArrayObjectItem($object1));
        $instance->addItem(new ArrayObjectItem($object2));

        $json = json_encode($instance);

        $this->assertEquals('[{"id":1,"name":"hello"},{"id":2,"name":"world"}]', $json);
    }

    public function testNotUnique()
    {
        $instance = new ArrayObject();

        $array = ['id' => 1, 'name' => 'hello'];

        $object = new \stdClass();
        $object->id = 1;
        $object->name = 'hello';

        $instance->addItem(new ArrayObjectItem($array));
        $instance->addItem(new ArrayObjectItem($object));

        $this->assertCount(2, $instance);
    }

    public function testUnique()
    {
        $instance = new ArrayObject(true);

        $array = ['id' => 1, 'name' => 'hello'];

        $object = new \stdClass();
        $object->id = 1;
        $object->name = 'hello';

        $instance->addItem(new ArrayObjectItem($array));
        $instance->addItem(new ArrayObjectItem($object));

        $this->assertCount(1, $instance);
    }
}