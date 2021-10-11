<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyJWTToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle (Request $request, Closure $next)
    {
        $this->checkForToken($request);

        try
        {
            if (!$this->auth->parseToken()->authenticate())
            {
                throw new UnauthorizedHttpException('jwt-auth', 'User not found');
            }
        }
        catch (JWTException $e)
        {
            try
            {
                $token    = $this->auth->parseToken()->refresh();
                $response = $next($request);

                return $this->setAuthenticationHeader($response, $token);
            }
            catch (JWTException $e)
            {
                throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
            }
        }

        return $next($request);
    }
}
