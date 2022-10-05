<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItemInterface;

trait ProblemTrait
{
    /**
     * @var ArrayObject|null
     */
    protected $problems;

    /**
     * @param mixed $problem
     * @return void
     */
    public function addProblem($problem)
    {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        $this->problems->addItem(new ArrayObjectItem($problem));
    }

    /**
     * @param $problem
     * @return bool
     */
    public function hasProblem($problem): bool
    {
        if (isset($this->problems)) {
            return $this->problems->hasItem($problem);
        }

        return false;
    }

    /**
     * @return ArrayObject|null
     */
    public function getProblems(): ?ArrayObject
    {
        return $this->problems;
    }

    /**
     * @param ArrayObject|null $problems
     */
    public function setTags(?ArrayObject $problems): void
    {
        $this->problems = $problems;
    }
}