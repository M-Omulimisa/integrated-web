<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;

class LogRouteMiddleware
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
        if ($request->bearerToken() || isset($request->client_id)) {
            // Log::error([
            //     'request'   => $request->all(),
            //     'content'   => $request->server('CONTENT_TYPE'),
            //     'http'      => $request->server('HTTP_CONTENT_TYPE'),
            //     'auth'      => $request->server('HTTP_AUTHORIZATION'),
            //     'accept'    => $request->server('HTTP_ACCEPT'),
            //     'cookie'    => $request->server('HTTP_COOKIE'),
            // ]);
        }

        return $next($request);
    }
}