<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtXAuth extends BaseMiddleware
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
        $payload = JWTAuth::payload();
        if ($payload->get('xtype') !== 'auth') {
            return response()->json([
                'status' => 'error',
                'message' => 'Token misused'
            ], 406);
        }

        return $next($request);
    }
}
