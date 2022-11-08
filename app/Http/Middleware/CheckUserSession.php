<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Middleware\OtpVerify;

/*
 * Global middleware to check for correct user hash and kick all old users out
 */
class CheckUserSession
{
    public function handle($request, Closure $next)
    {
        $userhash   = \Session::get('userhash');
        $sessionId = \Session::getId();
 
        if (!auth()->guest() && auth()->user()->user_hash != $userhash && !is_null(auth()->user()->user_hash)) {
            \Session::getHandler()->destroy($sessionId);

            // This kills other sessions for this particular user 
            auth()->user()->swap(true);
            
            // auth()->logout();
            // return redirect()->refresh();
            // return redirect()->route('login')->withErrors(trans('auth.swapped'));
        }
 
        return $next($request);
    }
}