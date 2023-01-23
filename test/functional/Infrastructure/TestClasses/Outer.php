<?php

namespace test\functional\Infrastructure\TestClasses;

class Outer
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Inner|null
     */
    protected $inner;

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
     * @return Inner|null
     */
    public function getInner(): ?Inner
    {
        return $this->inner;
    }

    /**
     * @param Inner|null $inner
     */
    public function setInner(?Inner $inner): void
    {
        $this->inner = $inner;
    }
}