<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomHttpException extends Exception
{
    private array $options;

    public function __construct ($message = "", $code = 0, Throwable $previous = null,
                                 array $options = [])
    {
        parent::__construct($message, $code, $previous);
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions () : array
    {
        return $this->options;
    }
}
