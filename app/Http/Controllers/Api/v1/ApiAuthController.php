<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utils;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthController extends Controller
{

    use ApiResponser;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $currentUrl = request()->path();
        $segments = explode('/', $currentUrl);
        $lastSegment = end($segments);
        if (!in_array($lastSegment, ['login', 'register'])) {
            $u = auth('api')->user();
            if ($u == null) {
                header('Content-Type: application/json');
                die(json_encode([
                    'code' => 0,
                    'message' => 'Unauthenticated',
                    'data' => null
                ]));
            }
        }
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $query = auth('api')->user();
        return $this->success($query, $message = "Profile details", 200);
    }


    public function login(Request $r)
    {

        if ($r->username == null) {
            return $this->error('Phone number or Email Address is required.');
        }

        if ($r->password == null) {
            return $this->error('Password is required.');
        }

        $r->username = trim($r->username);

        $u = User::where('email', $r->username)
            ->orWhere('phone', $r->username)
            ->orWhere('username', $r->username)
            ->first();


        if ($u == null) {
            return $this->error('User account not found.');
        }

        $token = auth('api')->attempt([
            'id' => $u->id,
            'password' => trim($r->password),
        ]);


        if ($token == null) {
            return $this->error('Wrong credentials.');
        }


        //auth('api')->factory()->setTTL(Carbon::now()->addMonth(12)->timestamp);

        JWTAuth::factory()->setTTL(60 * 24 * 30 * 365);


        $token = auth('api')->attempt([
            'id' => $u->id,
            'password' => trim($r->password),
        ]);


        if ($token == null) {
            return $this->error('Wrong credentials.');
        }
        $u->token = $token;
        $u->remember_token = $token;
        $u->roles_text = json_encode($u->roles);

        return $this->success($u, 'Logged in successfully.');
    }

    public function register(Request $r)
    {
        if ($r->name == null) {
            return $this->error('Name is required.');
        }

        if ($r->email == null) {
            return $this->error('Email address is required.');
        }

        if ($r->password == null) {
            return $this->error('Password is required.');
        }

        $u = User::where('email', $r->email)
            ->orWhere('phone', $r->email)
            ->orWhere('username', $r->email)
            ->first();
        if ($u != null) {
            return $this->error('User with same email address already exists.');
        }

        $u = User::where('email', $r->phone)
            ->orWhere('phone', $r->phone)
            ->orWhere('username', $r->phone)
            ->first();
        if ($u != null) {
            return $this->error('User with same phone number already exists.');
        }

        $user = new User();
        $user->name = $r->name;
        $user->phone = $r->phone;
        $user->email = $r->email;
        $user->username = $r->email;
        $user->photo = null;
        $user->password = password_hash(trim($r->password), PASSWORD_DEFAULT);
        try {
            $user->save();
        } catch (\Throwable $th) {
            return $this->error('Failed to create account because ' . $th->getMessage());
        }

        $new_user = User::where(['email' => $r->email])->first();
        if ($new_user == null) {
            return $this->error('Account created successfully but failed to log you in.');
        }
        Config::set('jwt.ttl', 60 * 24 * 30 * 365);

        $token = auth('api')->attempt([
            'id' => $new_user->id,
            'password' => $r->password,
        ]);

        $new_user->token = $token;
        $new_user->remember_token = $token;
        return $this->success($new_user, 'Account created successfully.');
    }
}
