<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\ArrayObject;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{
    public function testCallFunction()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
        ];
        $instance = new ArrayObject($data);

        $this->assertEquals('A', $instance->getA());
    }

    public function testToString()
    {
        $data = [
            'a' => 'A',
            'b' => 'B',
        ];
        $instance = new ArrayObject($data);

        $json = json_encode($instance);
        $string = (string) $instance;

        $this->assertEquals(json_encode($data), $json);
    }
}