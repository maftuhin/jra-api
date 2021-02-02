<?php

namespace App\Http\Middleware;

use Closure;

class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('appKey', config('APP_KEY')) == null) {
            return response([
                'code' => 401,
                'message' => 'Unauthorized credential',
            ], 401);
        }
        return $next($request);
    }
}
