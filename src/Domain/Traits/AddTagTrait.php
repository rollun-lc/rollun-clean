<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;

trait AddTagTrait
{
    /**
     * @var ArrayObject
     */
    protected $tags;

    public function addTag($value)
    {
        if ($this->tags === null) {
            $this->tags = new ArrayObject(true);
        }

        $this->tags->addItem(new ArrayObjectItem($value));
    }
}