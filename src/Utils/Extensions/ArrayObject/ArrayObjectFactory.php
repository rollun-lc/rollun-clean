<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

class ArrayObjectFactory
{
    public static function makeArrayObject(array $values, bool $unique = false): ArrayObject
    {
        $arrayObject = new ArrayObject($unique);
        foreach ($values as $value) {
            $item = new ArrayObjectItem($value);
            $arrayObject->addItem($item);
        }

        return $arrayObject;
    }
}