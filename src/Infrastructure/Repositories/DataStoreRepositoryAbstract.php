<?php

namespace Clean\Common\Infrastructure\Repositories;

use Clean\Common\Application\Interfaces\MapperInterface;
use Clean\Common\Domain\Entities\EntityAbstract;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

/**
 * TODO Зависимость от пакета rollun-datastore
 * @deprecated
 */
abstract class DataStoreRepositoryAbstract extends RepositoryAbstract
{
    /**
     * @var DataStoresInterface
     */
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