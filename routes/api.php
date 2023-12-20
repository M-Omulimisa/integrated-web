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
 

Route::get('/user', function (Request $request) {
    return 'Testing';
});
Route::get('/select-distcists', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        $conditions[] = ['name', 'like', '%' . $request->q . '%'];
    }
    $districts = \App\Models\DistrictModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});
Route::get('/select-subcounties', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        if ($request->has('by_id')) {
            $conditions['district_id'] = ((int)($request->q));
        } else {
            $conditions[] = ['name', 'like', '%' . $request->q . '%'];
        }
    }
    $districts = \App\Models\SubcountyModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});
Route::get('/select-parishes', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        if ($request->has('by_id')) {
            $conditions['subcounty_id'] = ((int)($request->q));
        } else {
            $conditions[] = ['name', 'like', '%' . $request->q . '%'];
        }
    }
    $districts = \App\Models\ParishModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});

Route::group([
    'prefix' => '/v1'
], function () {

    /* ==============START OF SHOP API================== */
    Route::get("orders", [ApiShopController::class, "orders_get"]);
    Route::get("vendors", [ApiShopController::class, "vendors_get"]);
    Route::post("become-vendor", [ApiShopController::class, 'become_vendor']);
    Route::get('products', [ApiShopController::class, 'products']);
    Route::POST("product-create", [ApiShopController::class, "product_create"]);
    Route::POST("post-media-upload", [ApiShopController::class, 'upload_media']);
    Route::POST('products-delete', [ApiShopController::class, 'products_delete']);
    Route::POST('chat-send', [ApiShopController::class, 'chat_send']);
    Route::get('chat-heads', [ApiShopController::class, 'chat_heads']);
    Route::get('chat-messages', [ApiShopController::class, 'chat_messages']);
    Route::POST('chat-mark-as-read', [ApiShopController::class, 'chat_mark_as_read']);
    Route::POST('chat-start', [ApiShopController::class, 'chat_start']);
    Route::post("orders", [ApiShopController::class, "orders_submit"]);
    Route::post("become-vendor", [ApiShopController::class, 'become_vendor']);

    /* ==============END OF SHOP API================== */


    // Authentication
    Route::post("request-otp-sms", [ApiAuthController::class, "request_otp_sms"]);
    Route::POST('login', [ApiAuthController::class, 'login']);
    Route::POST('register', [ApiAuthController::class, 'register']);
    Route::get('me', [ApiAuthController::class, 'me']);
    Route::get("users/me", [ApiAuthController::class, "me"]);
    Route::get('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_requests']);
    Route::get('my-roles', [ApiAuthController::class, 'my_roles']);
    Route::get('resources', [ApiAuthController::class, 'resources']);
    Route::get('resources-categories', [ApiAuthController::class, 'resources_categpries']);
    Route::POST('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_request_post']);
    Route::get('organisations', [ApiAuthController::class, 'organisations']);
    Route::POST('update-profile', [ApiAuthController::class, 'update_profile']);
    Route::get('farmer-groups', [ApiAuthController::class, 'farmer_groups']);
    Route::get('farmers', [ApiAuthController::class, 'farmers']);
    Route::POST('farmers', [ApiAuthController::class, 'farmers_create']);
    Route::get('countries', [ApiAuthController::class, 'countries']);
    Route::get('locations', [ApiAuthController::class, 'locations']);
    Route::get('languages', [ApiAuthController::class, 'languages']);
    Route::get('trainings', [ApiAuthController::class, 'trainings']);
    Route::get('farmer-questions', [ApiAuthController::class, 'farmer_questions']);
    Route::get('farmer_question_answers', [ApiAuthController::class, 'farmer_question_answers']);
    Route::get('training-sessions', [ApiAuthController::class, 'training_sessions']);
    Route::POST('training-sessions', [ApiAuthController::class, 'training_session_post']);
    Route::POST('gardens-create', [ApiAuthController::class, 'gardens_create']);
    Route::POST('farmer-questions-create', [ApiAuthController::class, 'farmer_questions_create']);
    Route::POST('farmer-answers-create', [ApiAuthController::class, 'farmer_answers_create']);

    Route::get('crops', [ApiAuthController::class, 'crops']);
    Route::get('gardens', [ApiAuthController::class, 'gardens']);
    Route::get('districts', [ApiAuthController::class, 'districts']);
    Route::get('resource-categories', [ApiAuthController::class, 'resource_categories']);
    Route::get('counties', [ApiAuthController::class, 'counties']);
    Route::get('regions', [ApiAuthController::class, 'regions']);
    Route::get('subcounties', [ApiAuthController::class, 'subcounties']);
    Route::get('parishes', [ApiAuthController::class, 'parishes']);
    Route::get('villages', [ApiAuthController::class, 'villages']);
    Route::get('permissions', [ApiAuthController::class, 'permissions']);
    Route::get('my-permissions', [ApiAuthController::class, 'my_permissions']);
    Route::get('roles', [ApiAuthController::class, 'roles']);

    Route::middleware('client_credentials')->group(function () {
        Route::POST('logout', function () {
            Route::POST('logout', [AuthApiController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('api/{model}', [ApiShopController::class, 'update']);
    Route::get('api/{model}', [ApiShopController::class, 'index']);
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

        // Route::POST('request', [ApiController::class, 'method']);

    });
});
