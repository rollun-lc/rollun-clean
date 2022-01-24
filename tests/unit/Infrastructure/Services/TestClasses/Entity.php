<?php

namespace unit\Infrastructure\Services\TestClasses;

use Clean\Common\Utils\Extensions\Collection;
use Clean\Common\Utils\Extensions\DateTime;

class Entity
{
    protected $id;

    /**
     * @var EntityInner
     */
    protected $inner;

    /**
     * @var \unit\Infrastructure\Services\TestClasses\EntityItem[]
     */
    protected $items;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @param int $id
     * @param array|Collection $items
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
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
     * @param \unit\Infrastructure\Services\TestClasses\EntityItem[] $items
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