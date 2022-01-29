<?php

namespace test\unit\Infrastructure\Services\TestClasses;

class EntityInnerDto
{
    public $id;

    public $title;

    /**
     * @var \test\unit\Infrastructure\Services\TestClasses\EntityInnerDto
     */
    public $inner;

    public function __construct($id)
    {
        $this->id = $id;
    }
}