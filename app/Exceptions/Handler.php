<?php

namespace App\Exceptions;

use App\Helpers\SharedFunctions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register ()
    {
        $this->renderable(function (InvalidAccountException $exception)
        {
            return response('Invalid username or password', 401);
        });

        $this->renderable(function (InvalidFormRequestException $exception)
        {
            return response('', 400);
        });
    }

    public function report (Throwable $e)
    {
        if ($e instanceof InvalidAccountException
            || $e instanceof InvalidFormRequestException)
        {
            return;
        }

        SharedFunctions::printError($e);
    }
}
