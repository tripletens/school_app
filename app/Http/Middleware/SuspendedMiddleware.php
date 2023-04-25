<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class SuspendedMiddleware
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
        $user = auth()->user();

        if ($user->status !== 2) {
            return ResponseHelper::error_response(
                'This user has been suspended. Contact admin',
                null,
                602
            );
        }

        return $next($request);
    }
}
