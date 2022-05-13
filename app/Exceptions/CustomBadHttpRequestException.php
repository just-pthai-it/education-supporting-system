<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomBadHttpRequestException extends Exception
{
    private array $options;

    public function __construct ($message = "", array $options = [], $code = 0,
                                 Throwable $previous = null)
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
