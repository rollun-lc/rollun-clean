<?php


namespace Clean\Common\Utils\Extensions;


use Clean\Common\Domain\Interfaces\ArrayableInterface;
use Clean\Common\Domain\Interfaces\JsonableInterface;

class Collection extends \ArrayIterator implements ArrayableInterface, JsonableInterface
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        $results = [];
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof ArrayableInterface) {
                $results[] = $item->toArray();
            } else {
                // TODO
                $results[] = (array) $item;
            }
        }

        return $results;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param string $key
     * @param callable|null $callback
     * @return Collection
     * @todo
     */
    public function mapWithKey(string $key, callable $callback = null)
    {
        $collection = new self();
        foreach ($this->getArrayCopy() as $item) {
            if (is_object($item)) {
                $getter = 'get' . $key;
                if (method_exists($item, $getter)) {
                    $name = $item->{$getter}();
                } elseif (property_exists($item, $key)) {
                    $name = $item->{$key};
                }
            } elseif (is_array($item)) {
                $name = $item[$key];
            } else {
                throw new \Exception('Can not mapWithKey collection with element type ' . gettype($item));
            }

            if ($callback) {
                $item = $callback($item);
            }
            $collection[$name] = $item;
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function map(callable $callback)
    {
        $collection = new self();
        foreach ($this->getArrayCopy() as $key => $item) {
            $item = $callback($item);
            $collection[$key] = $item;
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @return Collection
     */
    public function filter(callable $callback)
    {
        $collection = new self();
        foreach (array_filter($this->getArrayCopy(), $callback) as $key => $item) {
            $collection[$key] = $item;
        }

        return $collection;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    public function __toString()
    {
        return (string) json_encode($this->toArray());
    }

    public function merge(Collection $collection)
    {
        return new Collection(
            array_merge(
                $this->getArrayCopy(),
                $collection->getArrayCopy()
            )
        );
    }
}