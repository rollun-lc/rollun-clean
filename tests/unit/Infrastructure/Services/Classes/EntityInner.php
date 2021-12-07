<?php

namespace unit\Infrastructure\Services\Classes;

class EntityInner
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var EntityInner
     */
    protected $inner;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return EntityInner
     */
    public function getInner(): ?EntityInner
    {
        return $this->inner;
    }

    /**
     * @param EntityInner $inner
     */
    public function setInner(?EntityInner $inner): void
    {
        $this->inner = $inner;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}