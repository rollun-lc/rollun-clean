<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;

trait AddProblemTrait
{
    /**
     * @var ArrayObject
     */
    protected $problems;

    public function addProblem($value)
    {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        $this->problems->addItem(new ArrayObjectItem($value));
    }
}