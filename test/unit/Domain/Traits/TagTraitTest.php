<?php

namespace unit\Domain\Traits;

use Clean\Common\Domain\Traits\TagTrait;
use PHPUnit\Framework\TestCase;

class TagTraitTest extends TestCase
{
    public function testAddTag()
    {
        $instance = new class() extends \stdClass {
            use TagTrait;
        };

        $instance->addTagItem('hello');

        $property = new \ReflectionProperty($instance, 'tags');
        $property->setAccessible(true);
        $tags = $property->getValue($instance);

        $this->assertCount(1, $tags);

        $items = $tags->getItems();
        $item = array_shift($items);

        $this->assertEquals('#hello#', (string) $item);
    }

    public function testHasTag()
    {
        $instance = new class() extends \stdClass {
            use TagTrait;
        };

        $instance->addTagItem('#hello#');

        $result = $instance->hasTagItem('hello');

        $this->assertTrue($result);

        $result = $instance->hasTagItem('#hello#');

        $this->assertTrue($result);

        $result = $instance->hasTagItem('world');

        $this->assertFalse($result);
    }

    public function testDeleteTag()
    {
        $instance = new class() extends \stdClass {
            use TagTrait;
        };

        $instance->addTagItem('#hello#');

        $instance->deleteTagItem('hello');

        $property = new \ReflectionProperty($instance, 'tags');
        $property->setAccessible(true);
        $tags = $property->getValue($instance);

        $this->assertCount(0, $tags);
    }
}