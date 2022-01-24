<?php

namespace functional\Infrastructure\TestClasses;

use Clean\Common\Infrastructure\Repositories\DataStoreRepositoryAbstract;
use rollun\datastore\DataStore\Memory;

class TestDataStoreRepository extends DataStoreRepositoryAbstract
{
    protected function getModelClass(): string
    {
        return Test::class;
    }
}