<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;

trait ProblemTrait
{
    /**
     * @var ArrayObject
     */
    protected $problems;

    public function addProblem($problem)
    {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        $this->problems->addItem(new ArrayObjectItem($problem));
    }

    public function hasProblem($problem)
    {
        if (isset($this->problems)) {
            return $this->problems->hasItem($problem);
        }

        return false;
    }
}