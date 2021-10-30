<?php

namespace Clean\Common\Utils\Helpers;

class Str
{
    public static function toCamelCase($str, $lower = false)
    {
        if (self::isKebab($str)) {
            $str = self::fromKebabToCamel($str);
        }

        if (self::isSnake($str)) {
            $str = self::fromSnakeToCamel($str);
        }

        $str = $lower ? lcfirst($str) : ucfirst($str);

        return $str;
    }

    public static function toSnakeCase($str)
    {
        if (self::isKebab($str)) {
            return self::fromKebabToSnake($str);
        }

        return self::fromCamelToSnake($str);
    }

    public static function fromCamelToSnake($str)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $str, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    public static function fromKebabToSnake($str)
    {
        return str_replace('-', '_', $str);
    }

    public static function fromSnakeToCamel($str)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }

    public static function fromKebabToCamel($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    protected static function isSnake($str)
    {
        return strpos($str, '_') !== false;
    }

    protected static function isKebab($str)
    {
        return strpos($str, '-') !== false;
    }
}