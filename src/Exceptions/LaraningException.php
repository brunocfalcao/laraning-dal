<?php

namespace Laraning\DAL\Exceptions;

use Exception;

class LaraningException extends Exception
{
    public static function default($message = '', $code = -1)
    {
        return new static($message, $code);
    }
}
