<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItemInterface;

trait TagTrait
{
    /**
     * @var ArrayObject|null
     */
    protected $tags;

    /**
     * @param ArrayObjectItemInterface $tag
     * @return void
     */
    public function addTag(ArrayObjectItemInterface $tag): void
    {
        if ($this->tags === null) {
            $this->tags = new ArrayObject(true);
        }

        $this->tags->addItem($tag);
    }

    /**
     * @param $tag
     * @return bool
     */
    public function hasTag($tag): bool
    {
        if (isset($this->tags)) {
            return $this->tags->hasItem($tag);
        }

        return false;
    }

    /**
     * @return ArrayObject|null
     */
    public function getTags(): ?ArrayObject
    {
        return $this->tags;
    }

    /**
     * @param ArrayObject|null $tags
     */
    public function setTags(?ArrayObject $tags): void
    {
        $this->tags = $tags;
    }
}