<?php

namespace Clean\Common\Infrastructure\Adapters;

abstract class AdapterAbstract
{
    protected $api;

    public function __construct($api)
    {
        $this->api = $api;
    }
}