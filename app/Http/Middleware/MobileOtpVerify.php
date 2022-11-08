<?php

namespace App\Http\Middleware;

use Log;
use Auth;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Mobile\MobileAppOneTimePassword;
use Illuminate\Support\Facades\Route;

class MobileOtpVerify
{
    /**
     * Enables debug logging
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if ($this->debug) logger("entered middleware");

        if (\Auth::check()) {
            // get the logged in user
            $user = \Auth::user();

            if ($this->debug) logger(['USER' => $user]);

            // check for user OTP request in the database
            $otp = $user->getUserMobileOTP();

            // a record exists for the user in the database
            if ($otp instanceof MobileAppOneTimePassword) {

                if ($this->debug) logger("otp found");

                // if has a pending OTP verification request
                if ($otp->status == "waiting") {

                    // check timeout
                    if ($otp->isExpired()) {
                        if ($this->debug) logger("otp is expired");

                        $otp->discardOldPasswords();

                        \Auth::logout();

                        return $this->failure(__("otp.otp_expired"), 401);
                    } 
                    else {
                        if ($this->debug) logger("otp is valid, but status is waiting");

                        return $this->failure(str_replace(":method", \Auth::user()->two_auth_method, __("otp.otp_status_valid")));
                    }
                } else if ($otp->status == "verified") {
                    if ($this->debug) logger("otp is verified");

                    // verified request. go forth.
                    $response = $next($request);
                    if ($response->status() == 419) {

                        // timeout occured
                        if ($this->debug) logger("timeout occured");

                        $otp->discardOldPasswords();

                        \Auth::logout();

                        return $this->failure(__("otp.otp_expired"), 401);
                    } else {
                        if ($this->debug) logger("otp is valid, go forth");

                        // continue to next request
                        return $response;
                    }
                } else {
                    // invalid status, needs to login again.
                    if ($this->debug) logger("invalid status");

                    $otp->discardOldPasswords();

                    \Auth::logout();

                    return $this->failure(__("otp.otp_status_invalid"), 401);
                }
            } else {
                if ($this->debug) logger("otp doesn't exist");

                    \Auth::logout();

                    return $this->failure("otp doesn't exist", 401);
            }
        }
        else{
            // why are you here? we don't have anything to serve to you.
            return $this->failure('Unauthenticated.', 401);
        }

        if ($this->debug) logger("returning next request");

        // continue processing next request.

        return $next($request);
    }

    /**
     * Send failure response
     *
     * @param String/Array $message - response body
     * @param null $status -  response status code
     *
     * @return JsonResponse
     */
    public function failure($message, $status = null)
    {
        if (is_array($message)) {
            $message = $message[0];
        }

        return response()->json(
            [
                'message' => $message,
                'status' => $status ?? 400,
            ],
            $status ?? 400
        );
    }

    /**
     * Respond with a 401 that authorization failed
     * 
     * @param  string $message, the authorization failed, for display
     * @return PHP array, as json
     */
    public function unAuthorized($message=null)
    {
        $message = $message ?? 'Authorization failed';

        return $this->statusCode(401)
                    ->errorsFound($message);
    }
}
