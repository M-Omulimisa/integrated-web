<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Carbon\Carbon;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (auth()->user()) logger(auth()->user()->id);
        
        if (! $request->expectsJson()) {
            return route('login');
        }
        elseif ($request->expectsJson() || $request->bearerToken()) {
            return \Response::json([
                'data' => null,
                'message' => 'Unauthenticated',
                'status' => 401
            ], 401);
        }
    }
}