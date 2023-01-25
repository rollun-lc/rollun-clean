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

    public static function isAssociativeArray($array)
    {
        // Used in Laravel. It doesn't work with array like [0 => 1, 2 = 2]
        /*$keys = array_keys($array);
        return array_keys($keys) !== $keys;*/

        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}