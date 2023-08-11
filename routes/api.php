<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\Ussd\MenuController;
use App\Http\Controllers\Api\v1\ApiAuthController;
use App\Http\Controllers\Api\v1\ApiShopController;

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

    /* ==============START OF SHOP API================== */
    Route::get('api/{model}', [ApiShopController::class, 'index']);
    Route::get('products', [ApiShopController::class, 'products']);
    Route::POST("product-create", [ApiShopController::class, "product_create"]);
    Route::POST("post-media-upload", [ApiShopController::class, 'upload_media']);
    Route::post('products-delete', [ApiShopController::class, 'products_delete']);
    Route::post('chat-send', [ApiShopController::class, 'chat_send']);
    Route::get('chat-heads', [ApiShopController::class, 'chat_heads']);
    Route::get('chat-messages', [ApiShopController::class, 'chat_messages']);
    Route::post('chat-mark-as-read', [ApiShopController::class, 'chat_mark_as_read']);
    /* ==============END OF SHOP API================== */

    Route::put('api/{model}', [ApiShopController::class, 'update']);
    // Authentication
    Route::post('login', [ApiAuthController::class, 'login']);
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::get('me', [ApiAuthController::class, 'me']);
    Route::get('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_requests']);
    Route::get('my-roles', [ApiAuthController::class, 'my_roles']);
    Route::get('resources', [ApiAuthController::class, 'resources']);
    Route::get('resources-categories', [ApiAuthController::class, 'resources_categpries']);
    Route::post('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_request_post']);
    Route::get('organisations', [ApiAuthController::class, 'organisations']);
    Route::post('update-profile', [ApiAuthController::class, 'update_profile']);
    Route::get('farmer-groups', [ApiAuthController::class, 'farmer_groups']);
    Route::get('farmers', [ApiAuthController::class, 'farmers']);
    Route::post('farmers', [ApiAuthController::class, 'farmers_create']);
    Route::get('countries', [ApiAuthController::class, 'countries']);
    Route::get('locations', [ApiAuthController::class, 'locations']);
    Route::get('languages', [ApiAuthController::class, 'languages']);
    Route::get('trainings', [ApiAuthController::class, 'trainings']);
    Route::get('farmer-questions', [ApiAuthController::class, 'farmer_questions']);
    Route::get('farmer_question_answers', [ApiAuthController::class, 'farmer_question_answers']);
    Route::get('training-sessions', [ApiAuthController::class, 'training_sessions']);
    Route::post('training-sessions', [ApiAuthController::class, 'training_session_post']);
    Route::post('farmer-questions-create', [ApiAuthController::class, 'farmer_questions_create']);
    Route::post('farmer-answers-create', [ApiAuthController::class, 'farmer_answers_create']);

    Route::get('districts', [ApiAuthController::class, 'districts']);
    Route::get('counties', [ApiAuthController::class, 'counties']);
    Route::get('regions', [ApiAuthController::class, 'regions']);
    Route::get('subcounties', [ApiAuthController::class, 'subcounties']);
    Route::get('villages', [ApiAuthController::class, 'villages']);

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
