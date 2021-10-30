<?php

namespace Clean\Common\Infrastructure\Repositories;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Domain\Entities\EntityAbstract;

/**
 * TODO Зависимость от пакета rollun-datastore
 */
abstract class DataStoreRepositoryAbstract extends RepositoryAbstract
{
    protected $dataStore;

    public function __construct(
        DataStoresInterface $dataStore,
        MapperInterface $mapper = null
    ) {
        $this->dataStore = $dataStore;
        $this->mapper = $mapper;
    }

    public function getById($id): ?EntityAbstract
    {
        $data = $this->dataStore->read($id);
        if ($data) {
            return $this->createEntity($data);
        }

        return null;
    }

    public function delete($id)
    {
        return $this->dataStore->delete($id);
    }
}