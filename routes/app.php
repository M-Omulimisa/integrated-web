<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\v1\AuthMobileController;
use App\Http\Controllers\Mobile\v1\NinValidationMobileController;
use App\Http\Controllers\Mobile\v1\PhoneValidationMobileController;
use App\Http\Controllers\Mobile\v1\DashboardMobileController;

/*
|--------------------------------------------------------------------------
| MOBILE APP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Mobile App routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "app" middleware group. Enjoy building your API!
|
*/

Route::group([
    'namespace' => 'Mobile\v1',
    'prefix'=>'/v1'
], function () {

    // Authentication
    Route::post('login', [AuthMobileController::class, 'login']);
    // Route::post('refresh-token', [AuthApiController::class, 'refresh']);

    Route::middleware('client_credentials')->group(function(){
        Route::post('verify_otp', [AuthMobileController::class, 'verifyOTP']);
        Route::post('logout', [AuthMobileController::class, 'logout']);
    });

    Route::group(['middleware' => ['client_credentials', 'mobile_otp_verification']], function() {
        Route::get('user_profile', [AuthMobileController::class, 'account']);
        Route::get('dashboard', [DashboardMobileController::class, 'index']);        

        Route::post('validate_nin', [NinValidationMobileController::class, 'store']);
        Route::get('nin_validations', [NinValidationMobileController::class, 'index']);
        Route::get('nin_validation/{validation}', [NinValidationMobileController::class, 'show']);
        Route::get('nin_validation/report/{validation}', [NinValidationMobileController::class, 'report']);

        Route::post('validate_phone', [PhoneValidationMobileController::class, 'store']);
        Route::get('phone_validations', [PhoneValidationMobileController::class, 'index']);
        Route::get('phone_validation/{validation}', [PhoneValidationMobileController::class, 'show']);
        Route::get('phone_validation/report/{validation}', [PhoneValidationMobileController::class, 'report']);
    });

});