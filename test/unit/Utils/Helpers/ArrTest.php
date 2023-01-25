<?php

namespace unit\Utils\Helpers;

use Clean\Common\Utils\Helpers\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public function testIsAssociativeTrue()
    {
        $array = [
            'hello' => 'world',
            'test' => 1
        ];

        $result = Arr::isAssociativeArray($array);

        $this->assertTrue($result);
    }

    public function testIsAssociativeFalse()
    {
        $array = [
            'hello',
            'world'
        ];

        $result = Arr::isAssociativeArray($array);

        $this->assertFalse($result);
    }

    public function testIsAssociativeFalseNotOrdered()
    {
        $array = [
            0 => 'hello',
            2 => 'world'
        ];

        $result = Arr::isAssociativeArray($array);

        $this->assertFalse($result);
    }
}