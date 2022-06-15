<?php

namespace Clean\Common\Utils\Extensions\TypedCollection;

use Clean\Common\Utils\Extensions\Collection;

abstract class TypedCollectionAbstract extends Collection
{
    public const TYPE_INTEGER = 'integer';

    public const TYPE_DOUBLE = 'double';

    public const TYPE_STRING = 'string';

    public const TYPE_RESOURCE = 'resource';

    public const TYPE_NULL = 'NULL';

    public const TYPE_ARRAY = 'array';

    public const TYPE_BOOLEAN = 'boolean';

    public function __construct(object|array $array = [], int $flags = 0)
    {
        array_walk($array, [$this, 'checkType']);
        parent::__construct($array, $flags);
    }

    abstract protected function getType();

    protected function isValid($value)
    {
        if (is_object($value)) {
            return is_a($value, $this->getType(), true);
        }

        return gettype($value) === $this->getType();
    }

    protected function checkType($value)
    {
        if (!$this->isValid($value)) {
            throw new NotValidTypeException('Not valid type ' . gettype($value) . '. Expected ' . $this->getType());
        }
    }

    public function offsetSet(mixed $key, mixed $value)
    {
        $this->checkType($value);
        parent::offsetSet($key, $value);
    }

    public function append(mixed $value)
    {
        $this->checkType($value);
        parent::append($value);
    }

    public function column($column, $collection = null)
    {
        return $this->map(function ($item) use ($column) {
            return $this->getValueFromItem($item, $column);
        }, $collection);
    }

    public function mapTo($class, callable $callback)
    {
        $collection = $this->makeCollection($class);
        foreach ($this->getArrayCopy() as $key => $item) {
            $item = $callback($item, $key);
            $collection[$key] = $item;
        }

        return $collection;
    }

    public function mapWithKeyTo($class, $key, $callback = null)
    {
        $collection = $this->makeCollection($class);
        foreach ($this->getArrayCopy() as $item) {
            $name = $this->getValueFromItem($item, $key);
            if ($callback) {
                $item = $callback($item, $key);
            }
            $collection[$name] = $item;
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @param $collection
     * @return Collection|mixed
     * @see TypedCollectionAbstract::mapTo()
     * @todo
     */
    public function map(callable $callback, $class = null)
    {
        return $this->mapTo($class, $callback);
    }

    /**
     * @param string $key
     * @param callable|null $callback
     * @param $collection
     * @return Collection|mixed
     * @see TypedCollectionAbstract::mapWithKeyTo()
     * @todo
     */
    public function mapWithKey(string $key, callable $callback = null, $class = null)
    {
        return $this->mapWithKeyTo($class, $key, $callback);
    }

    public function chunk(int $length)
    {
        $result = new Collection();
        foreach (array_chunk($this->getArrayCopy(), $length) as $key => $group) {
            $collection = $this->newCollection($group);
            $result[] = $collection;
        }

        return $result;
    }

    public function groupByColumn($column)
    {
        $collection = new Collection();
        foreach ($this->getArrayCopy() as $key => $item) {
            $value = $this->getValueFromItem($item, $column);
            $group = $collection[$value] ?? null;
            if (!$group) {
                $group = new static();
                $collection[$value] = $group;
            }
            $group[] = $item;
        }

        return $collection;
    }

    protected function makeCollection($collection = null): Collection
    {
        if (!$collection) {
            $collection = static::class;
        }

        if (is_string($collection)) {
            $collection = new $collection();
        }

        if (!is_a($collection, Collection::class, true)) {
            throw new \Exception('Class must extend ' . Collection::class);
        }

        return $collection;
    }
}