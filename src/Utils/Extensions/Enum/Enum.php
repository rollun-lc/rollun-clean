<?php

namespace Clean\Common\Utils\Extensions\Enum;

abstract class Enum
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var bool
     */
    protected $nullable = false;

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

    public function __toString()
    {
        return (string) $this->value;
    }

    abstract protected function getAcceptableValues(): array;

    public function setNullable(bool $nullable)
    {
        $this->nullable = $nullable;
    }
}