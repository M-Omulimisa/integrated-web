<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Auth;
use Session;
 
class SessionExpired {
    protected $session;
    protected $timeout = 1 * 60;
     
    public function __construct(Store $session){
        $this->session = $session;
    }
    public function handle($request, Closure $next){
        $isLoggedIn = $request->path() != 'logout';
        \Log::info([
            'time' => time(),
            'last' => $this->session->get('lastActivityTime'),
            'diff' => time() - $this->session->get('lastActivityTime')
        ]);
        if(! session('lastActivityTime')){
            \Log::info('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
            $this->session->put('lastActivityTime', time());
        }            
        elseif(time() - $this->session->get('lastActivityTime') > $this->timeout){
            $this->session->forget('lastActivityTime');
            $cookie = cookie('intend', $isLoggedIn ? url()->current() : 'home');
            \Log::info('zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz');
            \Session::flash('fail', __("auth.session_timeout"));
            // auth()->user()->swap(true);
            auth()->logout();
        }
        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);
    }
}
