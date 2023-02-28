<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'status_code' => 401,
                    'data' => [],
                    'response' => 'Token is Invalid',
                    'debug' => [],
                ]);

                //// return response()->json(['status' => 'Token is Invalid']);
            } elseif (
                $e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException
            ) {
                //// ResponseHepler::error_response('Token is Expired', [], 401);

                return response()->json([
                    'status' => false,
                    'success' => false,
                    'status_code' => 401,
                    'data' => [],
                    'response' => 'Token is Expired',
                    'debug' => [],
                ]);

                // return response()->json(['status' => 'Token is Expired']);
            } else {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'status_code' => 401,
                    'data' => [],
                    'response' => 'Authorization Token not found',
                    'debug' => [],
                ]);
            }
        }

        return $next($request);
    }
}
