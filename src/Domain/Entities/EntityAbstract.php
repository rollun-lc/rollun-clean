<?php

namespace Clean\Common\Domain\Entities;

use Clean\Common\Domain\Interfaces\ArrayableInterface;

abstract class EntityAbstract implements ArrayableInterface
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function toArray(): array
    {
        $fields = $this->getArrayableFields();

        foreach ($fields as $field) {
            $value = $this->{$field};
            if (is_object($value)) {
                if ($value instanceof ArrayableInterface) {
                    $value = $value->toArray();
                } elseif (method_exists($value, '__toString')) {
                    $value = $value->__toString();
                }
            } elseif (is_array($value)) {
                $items = [];
                foreach ($value as $key => $item) {
                    if ($item instanceof ArrayableInterface) {
                        $item = $item->toArray();
                    }
                    $items[$key] = $item;
                }
                $value = $items;
            }
            $result[$field] = $value;
        }

        return $result;
    }

    abstract protected function getArrayableFields(): array;
}