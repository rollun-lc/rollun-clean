<?php

namespace unit\Utils\Extensions;

use Clean\Common\Utils\Extensions\Enum\Enum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testValidEnum()
    {
        $enum = new class() extends Enum {
            protected function getAcceptableValues(): array
            {
                return ['first', 'second'];
            }
        };
        $enum->setValue('first');
        $this->assertEquals('first', $enum->getValue());
    }

    public function testSetNullNotAllowed()
    {
        $enum = new class() extends Enum {
            protected function getAcceptableValues(): array
            {
                return ['first', 'second'];
            }
        };
        $this->expectExceptionMessageRegExp('/Invalid value .*?\. Acceptable value .+/');
        $enum->setValue(null);
    }

    public function testSetNullAllowed()
    {
        $enum = new class() extends Enum {
            protected $nullable = true;
            protected function getAcceptableValues(): array
            {
                return ['first', 'second'];
            }
        };
        $enum->setValue(null);
        $this->assertNull($enum->getValue());
    }
}