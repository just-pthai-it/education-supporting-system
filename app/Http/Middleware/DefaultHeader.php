<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;

class DefaultHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle (Request $request, Closure $next)
    {
        return $next($request)->header('Content-Type', 'application/json')
                              ->header('Access-Control-Expose-Headers', 'Authorization')
                              ->header('Access-Control-Allow-Headers', 'Authorization')
                              ->header('Access-Control-Allow-Methods',
                                       'HEAD, GET, POST, PUT, DELETE')
                              ->header('Access-Control-Allow-Headers', 'origin, x-requested-with')
                              ->header('Access-Control-Allow-Origin', '*')
                              ->header('Expires',
                                       (new DateTime('+5 seconds'))->format('D, d M Y H:i:s T'));
    }
}
