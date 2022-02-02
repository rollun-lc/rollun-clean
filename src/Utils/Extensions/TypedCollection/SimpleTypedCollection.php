<?php

namespace Clean\Common\Utils\Extensions\TypedCollection;

class SimpleTypedCollection extends TypedCollection
{
    protected $type;

    public function __construct(string $type, object|array $array = [], int $flags = 0)
    {
        $this->type = $type;
        parent::__construct($array, $flags);
    }

    protected function getType()
    {
        return $this->type;
    }

    public function map(callable $callback, $collection = null, $type = null)
    {

        return parent::map($callback, $collection);
    }
}