<?php

namespace Clean\Common\Utils\Extensions;

class Enum
{
    /**
     * @var array
     */
    protected $enum;

    /**
     * @var string
     */
    protected $value;

    public function __construct($enum)
    {
        $this->enum = $enum;
    }

    public function __toString()
    {
        return $this->value;
    }
}