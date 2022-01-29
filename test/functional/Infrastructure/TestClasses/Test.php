<?php

namespace test\functional\Infrastructure\TestClasses;

use Clean\Common\Domain\Entities\EntityAbstract;

class Test extends EntityAbstract
{
    protected $id;

    protected $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    protected function getArrayableFields(): array
    {
        // TODO: Implement getArrayableFields() method.
    }
}