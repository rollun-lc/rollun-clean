<?php

namespace Clean\Common\Application\Interfaces;

interface MapperInterface
{
    public const TYPE_ARRAY = 'array';

    public function map(mixed $data, string|object $type): mixed;

    public function mapToArray(object $data): array;

    public function mapFromArray(array $data, string|object $type): object;
}