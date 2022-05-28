<?php

namespace App\Exceptions;

use App\Helpers\GFunction;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     * @return void
     */
    public function register ()
    {
        $this->renderable(function (CustomBadHttpRequestException $e)
        {
            return response(['messages' => json_decode($e->getMessage())], $e->getCode());
        });

        $this->reportable(function (CustomBadHttpRequestException $e)
        {
            return false;
        });

        $this->renderable(function (CustomAuthenticationException $e)
        {
            return response(['messages' => json_decode($e->getMessage())], $e->getCode());
        });

        $this->reportable(function (CustomAuthenticationException $e)
        {
            return false;
        });
    }

    public function report (Throwable $e)
    {
        GFunction::printError($e);
    }
}
