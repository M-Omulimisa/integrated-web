<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckBlockedUser
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
        if (auth()->check() && auth()->user()->status == User::STATUS_INACTIVE ) {
            auth()->logout();
            $message = trans('auth.inactive');
            return redirect()->route('login')->withErrors($message);
        }
        elseif (auth()->check() && auth()->user()->status == User::STATUS_SUSPENDED ) {
            auth()->logout();
            $message = trans('auth.suspended');
            return redirect()->route('login')->withErrors($message);
        }
        elseif (auth()->check() && auth()->user()->status == User::STATUS_BANNED ) {
            auth()->logout();
            $message = trans('auth.banned');
            return redirect()->route('login')->withErrors($message);
        }

        // if (auth()->check() && auth()->user()->banned_until && now()->lessThan(auth()->user()->banned_until)) {
        //     $banned_days = now()->diffInDays(auth()->user()->banned_until);
        //     auth()->logout();

        //     if ($banned_days > 14) {
        //         $message = 'Your account has been suspended. Please contact administrator.';
        //     } else {
        //         $message = 'Your account has been suspended for '.$banned_days.' '.str_plural('day', $banned_days).'. Please contact administrator.';
        //     }

        //     return redirect()->route('login')->withMessage($message);
        // }

        return $next($request);
    }
}
