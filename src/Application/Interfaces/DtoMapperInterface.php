<?php

namespace Clean\Common\Application\Interfaces;

interface DtoMapperInterface
{
    public function map(object $instance, array $data);
}