<?php

namespace Clean\Common\Utils\Extensions;

class ArrayObject extends \ArrayObject implements \JsonSerializable
{
    public function __call($name, $arguments)
    {
        if (count($arguments) === 0 && preg_match('/^get([A-Z]{1}[\w]*$)/', $name, $matches)) {
            $property = lcfirst($matches[1]);
            return $this[$property] ?? null;
        }

        if (count($arguments) === 1 && preg_match('/^set([A-Z]{1}[\w]*$)/', $name, $matches)) {
            $property = lcfirst($matches[1]);
            return $this[$property] = $arguments[0];
        }

        throw new \Exception('Undefined method ' . $name);
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}