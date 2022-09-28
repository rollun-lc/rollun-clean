<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

interface ArrayObjectInterface
{
    public function addItem(ArrayObjectItemInterface $item): void;

    public function setUnique(bool $unique): void;
}