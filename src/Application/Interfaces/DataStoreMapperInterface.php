<?php

namespace Clean\Common\Application\Interfaces;

interface DataStoreMapperInterface
{
    public function fromStore(array $data): mixed;

    public function toStore(mixed $data): array;
}