<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseException extends Exception
{
    public function __construct ($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function render (Request $request) : Response
    {
        return response($this->message, $this->code);
    }
}
