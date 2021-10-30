<?php

namespace Clean\Common\Application\Services;

use Clean\Common\Application\Interfaces\DtoMapperInterface;

class DtoMapper implements DtoMapperInterface
{
    public function map(object $instance, array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($instance, $key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }
}