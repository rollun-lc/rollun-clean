<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\ArrayObject;
use Clean\Common\Utils\Extensions\JsonObject;
use PHPUnit\Framework\TestCase;

class JsonObjectTest extends TestCase
{
    public function testNumericIndex()
    {
        $data = [
            'A', 'B', 'C'
        ];
        $this->expectExceptionMessage('Numeric index not supported');

        $instance = new JsonObject($data);
    }

    public function testSerializeObject()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
            'c' => 'C',
        ];
        $instance = new JsonObject($data);
        $result = json_encode($instance);

        $this->assertEquals('{"a":"A","b":"B","c":"C"}', $result);
    }

    public function testCallMethod()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
            'c' => 'C',
        ];
        $instance = new JsonObject($data);

        $this->assertEquals('A', $instance->getA());
    }

    public function testObjectEncode()
    {
        $data = new \stdClass();
        $data->a = 'A';
        $data->b = 'B';
        $data->c = 'C';
        $instance = new JsonObject($data);

        $result = json_encode($instance);
        $this->assertEquals('{"a":"A","b":"B","c":"C"}', $result);
    }

    public function testObjectSet()
    {
        $data = new \stdClass();
        $data->a = 'A';
        $data->b = 'B';

        $instance = new JsonObject($data);

        $instance->set('c', 'C');

        $result = json_encode($instance);
        $this->assertEquals('{"a":"A","b":"B","c":"C"}', $result);
    }
}