<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

class ArrayObject implements ArrayObjectInterface, \Iterator, \JsonSerializable, \Countable
{
    protected $items;

    protected $unique;

    public function __construct(bool $unique = false)
    {
        $this->unique = $unique;
    }

    public function setUnique(bool $unique): void
    {
        if ($unique && !$this->unique) {
            $this->renewUnique();
        }

        $this->unique = $unique;
    }

    protected function renewUnique()
    {
        $items = $this->items;
        $this->items = [];
        $this->unique = true;
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(ArrayObjectItemInterface $item): void
    {
        if ($this->unique) {
            $this->items[$item->key()] = $item;
        } else {
            $this->items[] = $item;
        }
    }

    public function hasItem(mixed $item): bool
    {
        if (!$item instanceof ArrayObjectItemInterface) {
            $item = new ArrayObjectItem($item);
        }

        return array_key_exists($item->key(), $this->items);
    }

    public function deleteItem(mixed $item)
    {
        if (!$item instanceof ArrayObjectItemInterface) {
            $item = new ArrayObjectItem($item);
        }

        if ($this->unique) {
            unset($this->items[$item->key()]);
        } else {
            $this->items = array_filter($this->items, function (ArrayObjectItem $objectItem) use ($item) {
                return $item != $objectItem;
            });
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function rewind()
    {
        reset($this->items);
    }

    public function valid()
    {
        return key($this->items) !== null;
    }

    public function current()
    {
        return current($this->items);
    }

    public function next()
    {
        next($this->items);
    }

    public function key()
    {
        return key($this->items);
    }


    public function jsonSerialize()
    {
        return array_values($this->items);
    }

    public function count()
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        return array_map(function (ArrayObjectItem $item) {
            return $item->value();
        }, array_values($this->items));
    }
}