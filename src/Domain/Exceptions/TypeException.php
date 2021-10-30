<?php

namespace Clean\Common\Domain\Exceptions;

use Throwable;

class TypeException extends \Exception
{
    protected $message = 'Invalid type passed';
}