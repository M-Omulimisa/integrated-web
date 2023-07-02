<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\OneTimePassword;
use App\Models\Auth\OneTimePasswordLog;
use App\Http\Middleware\OtpVerify;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Class for handling OTP view display and processing
 */
class OtpController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OtpVerify $otpVerify)
    {
        $this->otpVerify = $otpVerify;
    }

    /**
     * Shows the OTP login screen
     *
     * @param  int  $id
     * @return View/Redirect
     */
    public function view(Request $request)
    {
        // this route is protected by WEB and AUTH middlewares, but still, this check can be useful.
        if (\Auth::check()) {

            // Check if user has already made a OTP request with a "waiting" status
            $otp = OneTimePassword::where([
                "user_id" => \Auth::user()->id,
                "status" => "waiting"
            ])->orderByDesc("created_at")->first();

            // if it exists
            if ($otp instanceof OneTimePassword) {
                // dd(Cache::get('pass'));
                // show the OTP validation form
                if (\Session::has('pass')) {
                    \Session::flash('success', \Session::get('pass'));
                } else {
                    \Session::flash('info', str_replace(":method", \Auth::user()->two_auth_method, __("otp.otp_status_valid")));
                }
                return view('auth.otp');

            } else {

                // the user hasn't done a request, why is he/she here? redirect back to login screen.
                return redirect(route('auth.otp.logout'))->withErrors(_("otp.unauthorized"));
            }
        } else {
            // the user hasn't tried to log in, why is he/she here? redirect back to login screen.
            return redirect('login')->withErrors(__("otp.unauthorized"));
        }
    }

    /**
     * Checks the given OTP
     *
     * @param Request $request
     * @return void
     */
    public function check(Request $request)
    {
        // if user has been logged in
        if (\Auth::check()) {

            // get the user for querying the verification code
            $user = \Auth::user();

            // check if current request has a verification code
            // if ($request->has("otp")) {
            if ($request->has('otp')) {

                // get the code entered by the user to check
                // $code = $request->input("otp");
                $code = $request->otp;

                // get the waiting verification code from database
                $otp = OneTimePassword::where([
                    "user_id" => $user->id,
                    "status" => "waiting"
                ])->orderByDesc("created_at")->first();

                // if the code exists
                if ($otp instanceof OneTimePassword) {

                    // check timeout
                    if ($otp->isExpired()) {

                        // expired. expire the cookie if exists
                        $this->otpVerify->createExpiredCookie();  

                        // expiry error message
                        \Session::flash('fail', __("otp.otp_expired"));

                        //  redirect to login page
                        return $this->otpVerify->logout($otp);
                    }
                    // compare it with the received code
                    else if ($otp->checkPassword($code)) {

                        // the codes match, set a cookie to expire in given time (timeout)
                        setcookie("otp_login_verified", "user_id_" . $user->id, time() + config("otp.otp_cookie_timeout"), "/", "", false, true);

                        // set the code status to "verified" in the database
                        $otp->acceptEntrance();

                        // redirect user to the login redirect path defined in the application

                        // get the application namespace
                        $namespace = \Illuminate\Container\Container::getInstance()->getNamespace();

                        // check if the stock login controller exists
                        $class = "\\" . $namespace . "Http\\Controllers\\Auth\\LoginController";
                        if (class_exists($class)) {

                            // create a new instance of this class to get the redirect path
                            $authenticator = new $class(new OtpVerify);

                            $user->setLastLogin();

                            // swap the user session to this new login
                            // This kills other sessions 
                            $user->swap();
 
                            // return redirect()->intended($this->redirectPath());

                            // redirect to the redirect after login page
                            return redirect('/home');
                        } else {

                            $user->setLastLogin();

                            //redirect to the root page
                            return redirect("/");
                        }
                    } else {

                        // the codes don't match, return an error.
                        return redirect(route("otp.view"))->withErrors(__("otp.code_mismatch"));
                    }
                } else {

                    // the code doesn't exist in the database, return an error.
                    return redirect(route("login"))->withErrors(__("otp.otp_expired"));
                }
            } else {

                // the code is missing, what should we compare to?
                return redirect(route("otp.view"))->withErrors(__("otp.code_missing"));
            }
        } else {

            // why are you here? we don't have anything to serve to you.
            return redirect(route("login"));
        }
    }

    public function resend()
    {   
        try {
            // this route is protected by WEB and AUTH middlewares, but still, this check can be useful.
            if (\Auth::check()) {

                // Check if user has already made a OTP request with a "waiting" status
                $otp = OneTimePassword::where([
                    "user_id" => \Auth::user()->id,
                    "status" => "waiting"
                ])->orderByDesc("created_at")->first();

                // if it exists
                if ($otp instanceof OneTimePassword) {

                    $otp->createOtpActivity('Resend');

                    // send the OTP to the user
                    if ($otp->send() == true) {

                        // update otp activity that OTP was sent
                        $otp->updateOtpActivityTimestamp('sent_at');

                        // set success message
                        \Session::flash('pass', str_replace(":method", \Auth::user()->two_auth_method, __("otp.otp_sent")));

                        // redirect to OTP verification screen
                        return redirect(route('otp.view'));
                    } else {

                        // update otp activity that it failed
                        $otp->updateOtpActivityTimestamp('send_failed_at');

                        // otp send failed, expire the cookie if exists
                        $this->otpVerify->createExpiredCookie();

                        // set error message
                        \Session::flash('fail', __("otp.service_not_responding"));

                        // send the user to login screen with error
                        return $this->otpVerify->logout($otp);
                    }
                } else {
                    // the user hasn't done a request, why is he/she here? redirect back to login screen.
                    return redirect(route('auth.otp.logout'))->withErrors(_("otp.otp_expired"));
                }
            } else {

                // the user hasn't tried to log in, why is he/she here? redirect back to login screen.
                return redirect('/')->withErrors(__("otp.unauthorized"));
            }

        } catch (Throwable $exception) {
            return redirect()->back()->withErrors(trans('strings.something_went_wrong'));
        }
    }
}
