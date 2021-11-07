<?php

namespace unit\Infrastructure\Services\Classes;

class EntityInnerDto
{
    public $id;

    public $title;

    public function __construct($id)
    {
        $this->id = $id;
    }
}