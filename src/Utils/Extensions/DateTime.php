<?php

namespace Clean\Common\Utils\Extensions;

class DateTime extends \DateTime
{
    /**
     * @var string
     */
    protected $format;

    public function __construct($date = 'now', \DateTimeZone $timezone = null, $format = 'Y-m-d H:i:s')
    {
        parent::__construct($date, $timezone);
        $this->format = $format;
    }

    public function __toString()
    {
        return $this->format($this->format);
    }
}