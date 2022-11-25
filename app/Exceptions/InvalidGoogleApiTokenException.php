<?php

namespace App\Exceptions;

use Throwable;

class InvalidGoogleApiTokenException extends BaseException
{
    public function __construct ($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
