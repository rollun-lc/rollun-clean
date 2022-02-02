<?php

namespace Clean\Common\Utils\Extensions\TypedCollection;

use Clean\Common\Utils\Extensions\Collection;

class TypedCollection extends TypedCollectionAbstract
{
    protected $type;

    public function __construct($type, object|array $array = [], int $flags = 0)
    {
        $this->type = $type;
        array_walk($array, [$this, 'checkType']);
        parent::__construct($array, $flags);
    }

    protected function getType()
    {
        return $this->type;
    }

    /**
     * @param callable $callback
     * @param $collection
     * @return Collection
     * @see TypedCollectionAbstract::mapTo()
     * @todo
     */
    public function map(callable $callback, $class = null)
    {
        $collection = $class ? new static($class) : new Collection();
        return $this->mapTo($collection, $callback);
    }

    /**
     * @param string $key
     * @param callable|null $callback
     * @param $collection
     * @return Collection
     * @see TypedCollectionAbstract::mapWithKeyTo()
     * @todo
     */
    public function mapWithKey(string $key, callable $callback = null, $class = null)
    {
        $collection = $class ? new static($class) : new Collection();
        return $this->mapWithKeyTo($collection, $key, $callback);
    }

    /*public function mapTo($class, callable $callback)
    {
        $collection = new static($class);
        return parent::map($callback, $collection);
    }*/

    /*protected function newCollection($collection = null, $type = null)
    {
        if (!$collection) {
            $collection = static::class;
        }

        if (is_string($collection)) {
            $collection = new $collection($type);
        }

        if (!is_a($collection, Collection::class, true)) {
            throw new \Exception('Class must extend ' . Collection::class);
        }

        return $collection;
    }*/
}