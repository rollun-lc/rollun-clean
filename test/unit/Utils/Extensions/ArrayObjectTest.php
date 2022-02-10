<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\ArrayObject;
use Clean\Common\Utils\Extensions\JsonObject;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{
    public function testSerializeArray()
    {
        $data = [
            'A', 'B', 'C'
        ];
        $instance = new ArrayObject($data);
        $result = json_encode($instance);

        $this->assertEquals('["A","B","C"]', $result);
    }

    public function testSerializeObject()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
            'c' => 'C',
        ];
        $instance = new ArrayObject($data);
        $result = json_encode($instance);

        $this->assertEquals('["A","B","C"]', $result);
    }

    /*public function testToString()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
        ];
        $instance = new ArrayObject($data);

        $json = json_encode($instance);
        $string = (string) $instance;

        $this->assertEquals(json_encode($data), $json);
    }*/
}