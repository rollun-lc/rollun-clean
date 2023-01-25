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
     * @param ArrayObjectItemInterface $problem
     * @return void
     */
    public function addProblemItem(mixed $problem)
    {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        if (!$problem instanceof ArrayObjectItem) {
            $problem = new ArrayObjectItem($problem);
        }

        $this->problems->addItem($problem);
    }

    /**
     * @param $problem
     * @return bool
     */
    public function hasProblemItem($problem): bool
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
    public function setProblems(?ArrayObject $problems): void
    {
        $this->problems = $problems;
    }
}