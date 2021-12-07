<?php

namespace unit\Infrastructure\Services\Classes;

class EntityInnerDto
{
    public $id;

    public $title;

    /**
     * @var \unit\Infrastructure\Services\Classes\EntityInnerDto
     */
    public $inner;

    public function __construct($id)
    {
        $this->id = $id;
    }
}