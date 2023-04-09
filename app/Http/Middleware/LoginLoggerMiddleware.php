<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Stevebauman\Location\Facades\Location;
use App\Models\LoginLogger;
use Illuminate\Support\Facades\Auth;

class LoginLoggerMiddleware
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
        $response = $next($request);

        // Perform action
        $ip = $request->ip();
        $current_location = Location::get($ip);
        $server = $request->server();

        $data = [
            'ip_address' => $request->ip(),
            'browser' => $request->server('HTTP_USER_AGENT'),
            'uid' => Auth::user()->id,
            'location_data' => json_encode($current_location),
            'login_date' => now(),
        ];

        LoginLogger::create($data);
        return $response;
    }
}
