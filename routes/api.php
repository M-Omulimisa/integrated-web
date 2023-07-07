<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\Ussd\MenuController;
use App\Http\Controllers\Api\v1\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::POST("users/login", function () {
    return "Hello World";
});

Route::get('/user', function (Request $request) {
    return 'Testing';
});

Route::group([
    'prefix' => '/v1'
], function () {

    // Authentication
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::get('me', [ApiAuthController::class, 'me']);
    Route::get('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_requests']);
    Route::get('resources', [ApiAuthController::class, 'resources']);
    Route::post('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_request_post']);
    Route::get('organisations', [ApiAuthController::class, 'organisations']);
    Route::post('update-profile', [ApiAuthController::class, 'update_profile']);



    Route::middleware('client_credentials')->group(function () {
        Route::post('logout', function () {
            Route::post('logout', [AuthApiController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::group([
    'namespace' => 'Api\v1\Ussd',
    'prefix' => '/v1'
], function () {

    Route::get('ussdmenu', [MenuController::class, 'index']);
});

Route::group([
    'namespace' => 'Api\v1\Mobile',
    'prefix' => '/v1'
], function () {

    Route::middleware('client_credentials', 'passport.client.set')->group(function () {

        // Route::post('request', [ApiController::class, 'method']);

    });
});
