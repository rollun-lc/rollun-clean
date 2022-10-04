<?php

namespace unit\Utils\Extensions\ArrayObject;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectFactory;
use PHPUnit\Framework\TestCase;

class ArrayObjectFactoryTest extends TestCase
{
    public function testFactory()
    {
        $arrayObject = ArrayObjectFactory::make(['hello', 'world', 'hello'], true);

        $this->assertCount(2, $arrayObject);
    }
}