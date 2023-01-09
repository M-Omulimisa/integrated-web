<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use Log;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Ussd\UssdSession;

class MenuFunctions
{

    public function getLastMenu($sessionId, $phoneNumber)
    {
        $session = UssdSession::where('session_id', $sessionId)->where('phone_number', $phoneNumber)->first();
        return $session ? $session->last_menu : null;
    }

    public function checkSession($sessionId, $phoneNumber)
    {
        //check for the sessions existence
        $sessions = UssdSession::where('session_id', $sessionId)->where('phone_number', $phoneNumber)->count();
        return $sessions > 0 ? true : false;
    }

    public function saveLastMenu($sessionId, $phoneNumber, $current_menu)
    {
        $last_session = new UssdSession;

        if ($this->checkSession($sessionId, $phoneNumber)) {
            UssdSession::where('session_id', $sessionId)->where('phone_number', $phoneNumber)
                            ->update(['last_menu' => $current_menu]);
        } 
        else {
            //create new session if does not exist
            UssdSession::create([
                'session_id'    => $sessionId,
                'phone_number'  => $phoneNumber,
                'last_menu'     => $current_menu
            ]);
        }
    }
}
