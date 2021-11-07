<?php

namespace Clean\Common\Domain\Interfaces;

interface JsonableInterface
{
    public function toJson(): string;
}