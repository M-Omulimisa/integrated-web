<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use DB;
use Auth;
use Throwable;
use Validator;
use App\Models\User;
use App\Traits\Notification;
use App\Http\Middleware\OtpVerify;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{

    use ThrottlesLogins;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    // protected $redirectTo = RouteServiceProvider::HOME;

    // max number of times a user tries to login
    protected $maxAttempts = 3;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OtpVerify $otpVerify)
    {
        $this->middleware('guest')->except('logout');
        $this->otpVerify = $otpVerify;
    }

    public function showLoginForm()
    {
        if (\Auth::check() || \Session::has('auto_logout')) {
            return redirect(route('auth.otp.logout'))->withErrors(trans('auth.auto_logout'));
        }
        if (\Session::has('fail')) {
            return view('auth.login')->withErrors(\Session::get('fail'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email'     => 'required|email',
            'password'  => 'required',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->getMessageBag()->first());
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            'email'      => $request->email,
            'password'   => $request->password,
            'status'     => User::STATUS_ACTIVE,
        ];

        if (!Auth::attempt($credentials)) {

            if ($user = User::where('email', $request->email)->first()) {
                if ($user->status == User::STATUS_INACTIVE) {
                    return redirect()->back()->withErrors(trans('auth.inactive'));
                }
                elseif ($user->status == User::STATUS_SUSPENDED) {
                    return redirect()->back()->withErrors(trans('auth.suspended'));
                }
                elseif ($user->status == User::STATUS_BANNED) {
                    return redirect()->back()->withErrors(trans('auth.banned'));
                }
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return redirect()->back()->withErrors(trans('auth.failed'));
        }

        // Clear the login locks for the given user credentials.
        // $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        DB::beginTransaction();

        try {
            // Get user and save login session
            $user = auth()->user();
            $device = $request->header('User-Agent');
            // save the session
            $user->userSessions()->create([
                'status'        => 'Logged-in', 
                'ip_address'    => $request->ip(),
                'expires'       => config('session.lifetime'), 
                'user_agent'    => $device,
                'payload'       => serialize($request->session())
            ]);

            // get the active otp sent to user
            $otp = $this->otpVerify->getUserOTP($user); 
            // dicard all active otps
            $otp->discardOldPasswords('true');

            DB::commit();

            return redirect('/home')->with('success', str_replace(":method", auth()->user()->two_auth_method, trans('otp.otp_sent')));

        } catch (Throwable $throwable) {
            DB::rollBack();

            return redirect()->back()->withErrors('Failed, try again to login. '.$throwable->getMessage());
        }
    }

    public function logout(Request $request)
    {    
        try {
            if (auth()->user()) {

                // Get user and save logout activity
                $user = auth()->user();
                $device = $request->header('User-Agent');

                // save the session activity
                $user->userSessions()->create([
                    'status'    => 'Logged-out',
                    'ip_address'=> $request->ip(), 
                    'user_agent'    => $device,
                    'expires'   => config('session.lifetime'), 
                    'payload' => serialize($request->session())
                ]);
            }    

            // get the active otp sent to user
            $otp = $this->otpVerify->getUserOTP($user);   
            // check for user OTP request in the database 
            if ($otp) $this->otpVerify->logout($otp);

            // Log out the user
            auth()->logout(true);
            return redirect('/login/verify')->with('success', trans('strings.logged_out_successfully'));

        } catch (Throwable $throwable) {

            return redirect()->back()->withErrors($throwable->getMessage());
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /*

    // Open a try/catch block
    try {
        // Begin a transaction
        DB::beginTransaction();

        // Do something and save to the db...

        // Commit the transaction
        DB::commit();
    } catch (\Exception $e) {
        // An error occured; cancel the transaction...
        DB::rollback();

        // and throw the error again.
        throw $e;
    }

    */


}
