<?php

namespace unit\Infrastructure\Services\Classes;

use Clean\Common\Utils\Extensions\Collection;

class Entity
{
    protected $id;

    /**
     * @var EntityInner
     */
    protected $inner;

    /**
     * @var \unit\Infrastructure\Services\Classes\EntityItem[]
     */
    protected $items;

    /**
     * @param int $id
     * @param array|Collection $items
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return EntityItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param \unit\Infrastructure\Services\Classes\EntityItem[] $items
     */
    public function setItems(Collection $items)
    {
        $this->items = $items;
    }

    /**
     * @return EntityInner
     */
    public function getInner(): EntityInner
    {
        return $this->inner;
    }

    /**
     * @param EntityInner $inner
     */
    public function setInner(EntityInner $inner): void
    {
        $this->inner = $inner;
    }
}