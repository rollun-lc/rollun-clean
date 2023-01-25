<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItemInterface;

trait TagTrait
{
    protected $enclosure = '#';

    /**
     * @var ArrayObject|null
     */
    protected ?ArrayObject $tags = null;

    protected function enclose(string $tag)
    {
        $tag = trim($tag, $this->enclosure);
        return $this->enclosure . $tag . $this->enclosure;

    }

    /**
     * @param string $tag
     * @return void
     */
    public function addTagItem(ArrayObjectItem|string $tag): void
    {
        $tag = $this->enclose($tag);
        if ($this->tags === null) {
            $this->tags = new ArrayObject(true);
        }
        
        if (is_string($tag)) {
            $tag = new ArrayObjectItem($tag);
        }

        $this->tags->addItem($tag);
    }

    /**
     * @param $tag
     * @return bool
     */
    public function hasTagItem($tag): bool
    {
        $tag = $this->enclose($tag);
        if (isset($this->tags)) {
            return $this->tags->hasItem($tag);
        }

        return false;
    }

    public function deleteTagItem(string $tag)
    {
        $tag = $this->enclose($tag);
        if ($this->tags !== null) {
            $this->tags->deleteItem($tag);
        }
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