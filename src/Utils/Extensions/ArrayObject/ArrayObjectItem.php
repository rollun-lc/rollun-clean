<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

class ArrayObjectItem implements ArrayObjectItemInterface, \JsonSerializable, \Stringable
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string|array $value
     */
    public function __construct($value)
    {
        if (is_object($value)) {
            $value = $this->toArray($value);
        }

        $this->value = $value;
    }

    private function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    public function key(): string
    {
        return md5((string) $this);
    }

    public function value()
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }

    public function __toString()
    {
        if (is_array($this->value)) {
            $result = json_encode($this);

            if (is_string($result)) {
                return $result;
            }

            throw new \Exception('Can not serialize');
        }

        return (string) $this->value;
    }
}