<?php

namespace functional\Infrastructure\TestClasses;

use rollun\datastore\DataStore\DataStoreAbstract;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

class DataStoreAdapter extends DataStoreAbstract implements DataStoresInterface
{
    protected $dataStore;

    public function __construct(DataStoreInterface $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    public function create($itemData, $rewriteIfExist = false)
    {
        $this->dataStore->create($itemData);
    }

    public function update($itemData, $createIfAbsent = false)
    {
        $this->dataStore->update($itemData);
    }

    public function delete($id)
    {
        $this->dataStore->delete($id);
    }
}