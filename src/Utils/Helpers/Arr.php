<?php

namespace Clean\Common\Utils\Helpers;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;

class Arr
{
    public static function mergeRecursive($array1, $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => & $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::mergeRecursive($merged[$key], $value);
            } else if (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    public static function toArrayObject(array $items, bool $unique = true)
    {
        $instance = new ArrayObject($unique);
        foreach ($items as $item) {
            $instance->addItem(new ArrayObjectItem($item));
        }

        return $instance;
    }
}