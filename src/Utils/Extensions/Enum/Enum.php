<?php

namespace Clean\Common\Utils\Extensions\Enum;

abstract class Enum implements \Stringable, \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var bool
     */
    protected $nullable = false;

    public function __construct($value = null)
    {
        if ($value) {
            $this->setValue($value);
        }
    }

    /**
     * @param $value
     * @return void
     * @throws EnumException
     */
    public function setValue($value)
    {
        $allowed = $this->getAcceptableValues();
        if ($this->nullable) {
            $allowed[] = null;
        }
        if (!in_array($value, $allowed)) {
            throw new EnumException(
                'Invalid value ' . $value . '. Acceptable value ' . implode(', ', $allowed)
            );
        }
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    public function jsonSerialize()
    {
        return $this->__toString();
    }

    abstract protected function getAcceptableValues(): array;

    public function setNullable(bool $nullable)
    {
        $this->nullable = $nullable;
    }
}