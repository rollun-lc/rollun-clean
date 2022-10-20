<?php

namespace Clean\Common\Infrastructure\Mappers;

interface DataStoreMapperInterface
{
    public function fromStore(array $data): mixed;

    public function toStore(mixed $data): array;
}