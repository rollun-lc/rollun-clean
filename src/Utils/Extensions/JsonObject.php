<?php

namespace Clean\Common\Utils\Extensions;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class JsonObject extends ArrayObject
{
    public function __construct(object|array $array = [], int $flags = 0, string $iteratorClass = "ArrayIterator")
    {
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                /*$hash = md5($key);
                $array[$hash] = $value;
                unset($array[$key]);*/
                throw new \Exception('Numeric index not supported');
            }
        }

        parent::__construct($array, $flags, $iteratorClass);
    }

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

    public function get($key)
    {
        if (isset($this[$key])) {
            return $this[$key];
        }

        // TODO Maybe exception
        return null;
    }

    public function set($key, $value)
    {
        $this[$key] = $value;
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}