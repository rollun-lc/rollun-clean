<?php


namespace Clean\Common\Utils\Extensions;


use Clean\Common\Domain\Interfaces\ArrayableInterface;

class Collection extends \ArrayIterator implements ArrayableInterface
{
    public function toArray(): array
    {
        $results = [];
        foreach ($this->getArrayCopy() as $item) {
            if ($item instanceof ArrayableInterface) {
                $results[] = $item->toArray();
            } else {
                $results[] = $item;
            }
        }

        return $results;
    }

    public function toJson(bool $originKeys = false): string
    {
        // TODO: Implement toJson() method.
    }

    public function mapWithKey(string $key, callable $callback = null)
    {
        $collection = new self();
        foreach ($this->getArrayCopy() as $item) {
            $name = \JmesPath\Env::search($key, $item);
            if ($callback) {
                $item = $callback($item);
            }
            $collection[$name] = $item;
        }

        return $collection;
    }

    public function map(callable $callback)
    {
        $collection = new self();
        foreach ($this->getArrayCopy() as $key => $item) {
            $item = $callback($item);
            $collection[$key] = $item;
        }

        return $collection;
    }

    public function filter(callable $callback)
    {
        $collection = new self();
        foreach (array_filter($this->getArrayCopy(), $callback) as $key => $item) {
            $collection[$key] = $item;
        }

        return $collection;
    }

    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    public function __toString()
    {
        return (string) json_encode($this->toArray());
    }
}