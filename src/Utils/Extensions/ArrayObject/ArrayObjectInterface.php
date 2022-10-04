<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

interface ArrayObjectInterface
{
    public function addItem(ArrayObjectItemInterface $item): void;

    public function hasItem($item): bool;

    public function setUnique(bool $unique): void;

    public function toArray(): array;
}