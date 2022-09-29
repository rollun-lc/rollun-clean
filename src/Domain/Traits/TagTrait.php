<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;

trait TagTrait
{
    /**
     * @var ArrayObject
     */
    protected $tags;

    public function addTag($tag)
    {
        if ($this->tags === null) {
            $this->tags = new ArrayObject(true);
        }

        $this->tags->addItem(new ArrayObjectItem($tag));
    }

    public function hasTag($tag)
    {
        if (isset($this->tags)) {
            return $this->tags->hasItem($tag);
        }

        return false;
    }
}