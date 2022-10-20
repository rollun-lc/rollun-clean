<?php

namespace Clean\Common\Infrastructure\Mappers;

use Clean\Common\Application\Interfaces\MapperInterface;

class SimpleDataStoreMapper extends DataStoreMapperAbstract
{
    protected $modelClass;

    public function __construct(MapperInterface $mapper, string $modelClass)
    {
        parent::__construct($mapper);

        $this->modelClass = $modelClass;
    }

    protected function getModelClass(): string
    {
        return $this->modelClass;
    }
}