<?php

namespace test\functional\Infrastructure\TestClasses;

use Clean\Common\Domain\Entities\EntityAbstract;
use Clean\Common\Infrastructure\Repositories\RepositoryAbstract;

class TestRepository extends RepositoryAbstract
{
    protected $items;

    public function __construct($items)
    {
        foreach ($items as $item) {
            if (method_exists($item, 'getId')) {
                $key = $item->getId();
            } elseif (property_exists($item, 'id')) {
                $key = $item->id;
            }
            $this->items[$key] = $item;
        }
    }

    protected function getModelClass(): string
    {
        return Test::class;
    }

    public function getById($id): Test
    {
        return $this->items[$id];
    }

    public function delete($id): void
    {
        unset($this->items[$id]);
    }
}