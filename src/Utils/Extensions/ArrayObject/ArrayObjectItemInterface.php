<?php

namespace Clean\Common\Utils\Extensions\ArrayObject;

interface ArrayObjectItemInterface
{
    public function key(): string;

    public function value();
}