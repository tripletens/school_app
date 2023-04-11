<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
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
        if (strtolower($request->header('ROLE_ACCESS')) != 'student') {
            return response()->json(
                ResponseHelper::error_response('Access denied', null, 401)
            );
        }
        return $next($request);
    }
}