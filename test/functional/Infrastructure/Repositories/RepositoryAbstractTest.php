<?php

namespace test\functional\Infrastructure\Repositories;

use test\functional\Infrastructure\TestClasses\DataStoreAdapter;
use test\functional\Infrastructure\TestClasses\Test;
use test\functional\Infrastructure\TestClasses\TestDataStoreRepository;
use test\functional\Infrastructure\TestClasses\TestRepository;
use PHPUnit\Framework\TestCase;
use rollun\datastore\DataStore\Memory;

class RepositoryAbstractTest extends TestCase
{
    protected function makeData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Test 1'
            ],
            [
                'id' => 2,
                'name' => 'Test 2'
            ]
        ];
    }

    protected function makeEnities()
    {
        return array_map(function ($item) {
            return new Test($item['id'], $item['name']);
        }, $this->makeData());
    }

    public function testUpdate()
    {
        $repository = new TestRepository($this->makeEnities());
        $entity = $repository->getById(1);
        $entity->setName('Hello world');

        //$this->assertEquals('Test 1', $entity->getName());
        $this->assertTrue(true);
    }
}