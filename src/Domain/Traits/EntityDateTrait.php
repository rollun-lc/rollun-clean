<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\DateTime;
use DateTimeInterface;

trait EntityDateTrait
{
    protected $createAtField = 'createdAt';

    protected $updatedAtField = 'updatedAt';

    protected $format = 'Y-m-d H:i:s.v';

    protected $timezone = 'UTC';

    protected $createdAt;

    protected $updatedAt;

    public function getCreatedAt()
    {
        $field = $this->getCreatedAtField();
        if (!property_exists($this, $field)) {
            throw new \Exception('Field ' . $field . ' does not exist');
        }

        //return $this->{$field};
        if ($this->{$field}) {
            return new DateTime($this->{$field}, new \DateTimeZone($this->timezone), $this->format);
        }

        return null;
    }

    public function getUpdatedAt()
    {
        $field = $this->getUpdatedAtField();
        if (!property_exists($this, $field)) {
            throw new \Exception('Field ' . $field . ' does not exist');
        }

        //return $this->{$field};
        if ($this->{$field}) {
            return new DateTime($this->{$field}, new \DateTimeZone($this->timezone), $this->format);
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function setCreatedAt($date = null)
    {
        $field = $this->getCreatedAtField();
        if (!property_exists($this, $field)) {
            throw new \Exception('Field ' . $field . ' does not exist');
        }

        if (!$date instanceof DateTimeInterface) {
            $date = new DateTime($date, new \DateTimeZone($this->getTimezone()), $this->format);
        }

        $this->{$field} = $date->format($this->getFormat());
    }

    public function setUpdatedAt($date = null)
    {
        $field = $this->getUpdatedAtField();
        if (!property_exists($this, $field)) {
            throw new \Exception('Field ' . $field . ' does not exist');
        }

        if (!$date instanceof DateTimeInterface) {
            $date = new DateTime('now', new \DateTimeZone($this->getTimezone()), $this->format);
        }

        $this->{$field} = $date->format($this->getFormat());
    }

    /**
     * @throws \Exception
     */
    public function renewUpdatedAt()
    {
        $date = new DateTime('now', new \DateTimeZone($this->getTimezone()), $this->format);
        $this->{$this->getUpdatedAtField()} = $date->format($this->getFormat());
    }

    /**
     * @return string
     */
    protected function getCreatedAtField()
    {
        return $this->createAtField;
    }

    /**
     * @return string
     */
    protected function getUpdatedAtField()
    {
        return $this->updatedAtField;
    }

    /**
     * @return string
     */
    protected function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    protected function getTimezone()
    {
        return $this->timezone;
    }
}