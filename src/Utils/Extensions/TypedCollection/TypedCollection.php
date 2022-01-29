<?php

namespace Clean\Common\Utils\Extensions\TypedCollection;

use Clean\Common\Utils\Extensions\Collection;

abstract class TypedCollection extends Collection
{
    public const TYPE_INTEGER = 'integer';

    public const TYPE_DOUBLE = 'double';

    public const TYPE_STRING = 'string';

    public const TYPE_RESOURCE = 'resource';

    public const TYPE_NULL = 'NULL';

    public const TYPE_ARRAY = 'array';

    public const TYPE_BOOLEAN = 'boolean';

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
}