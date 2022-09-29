<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

interface ArrayObjectInterface
{
    public function addItem(ArrayObjectItemInterface $item): void;

    public function hasItem(mixed $item): bool;

    public function setUnique(bool $unique): void;
}