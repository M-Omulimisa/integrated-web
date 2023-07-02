<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthApiController;

use App\Http\Controllers\Api\v1\Ussd\MenuController;

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

Route::get('/user', function (Request $request) {
        return 'Testing';
    });

Route::group([
    'namespace' => 'Api\v1',
    'prefix'=>'/v1'
], function () {

    // Authentication
    Route::post('login', [AuthApiController::class, 'login']);
    // Route::post('refresh-token', [AuthApiController::class, 'refresh']);

    Route::middleware('client_credentials')->group(function(){
        Route::post('logout', function() {
            Route::post('logout', [AuthApiController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::group([
    'namespace' => 'Api\v1\Ussd',
    'prefix'=>'/v1'
], function () {

    Route::get('ussdmenu', [MenuController::class, 'index']);     

});

Route::group([
    'namespace' => 'Api\v1\Mobile',
    'prefix'=>'/v1'
], function () {

    Route::middleware('client_credentials', 'passport.client.set')->group(function(){

        // Route::post('request', [ApiController::class, 'method']);

    }); 

});