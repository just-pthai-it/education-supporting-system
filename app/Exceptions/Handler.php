<?php

namespace App\Exceptions;

use Google\Service\Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     * @var array
     */
    protected $dontReport = [
        CustomHttpException::class,
        CustomAuthenticationException::class,
        CustomBadHttpRequestException::class,
        UnauthorizedHttpException::class,
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
        $this->renderable(function (Exception $e)
        {
            if (in_array($e->getCode(), [400, 401]))
            {
                return response(['message' => 'Token exception'], 422);
            }

            throw $e;
        });

        $this->renderable(function (CustomHttpException $e)
        {
            $messages = json_decode($e->getMessage()) ?? $e->getMessage();
            return response(['messages' => $messages], $e->getCode());
        });

        $this->renderable(function (CustomBadHttpRequestException $e)
        {
            return response(['messages' => json_decode($e->getMessage())], $e->getCode());
        });

        $this->renderable(function (CustomAuthenticationException $e)
        {
            return response(['messages' => json_decode($e->getMessage())], $e->getCode());
        });

        $this->renderable(function (DatabaseConflictException $e)
        {
            return response(['messages' => json_decode($e->getMessage())], $e->getCode());
        });
    }
}
