<?php

namespace test\unit\Infrastructure\Services\TestClasses;

class EntityItemDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}