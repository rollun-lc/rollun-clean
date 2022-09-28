<?php

namespace unit\Utils\Extensions\ArrayObject;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;
use PHPUnit\Framework\TestCase;

class ArrayObjectItemTest extends TestCase
{
    public function testJsonIndexedArray()
    {
        $string = ['hello', 'world'];
        $instance = new ArrayObjectItem($string);

        $result = json_encode($instance);

        $this->assertEquals(json_encode($string), $result);
    }

    public function testJsonAssociativeArray()
    {
        $string = ['id' => 123, 'title' => 'hello world'];
        $instance = new ArrayObjectItem($string);

        $result = json_encode($instance);

        $this->assertEquals(json_encode($string), $result);
    }

    public function testJsonString()
    {
        $string = 'Hello world';
        $instance = new ArrayObjectItem($string);

        $result = json_encode($instance);

        $this->assertEquals(json_encode($string), $result);
    }
}