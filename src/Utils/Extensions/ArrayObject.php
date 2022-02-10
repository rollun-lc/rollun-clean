<?php

namespace Clean\Common\Utils\Extensions;

class ArrayObject extends \ArrayObject implements \JsonSerializable, \Stringable
{
    public function jsonSerialize()
    {
        return array_values($this->getArrayCopy());
    }

    public function __toString(): string
    {
        return json_encode($this);
    }
}