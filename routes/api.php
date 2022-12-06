<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthApiController;

use App\Http\Controllers\AsigmaApi\v1\CreditAccountApiController;
use App\Http\Controllers\AsigmaApi\v1\RepaymentsApiController;
use App\Http\Controllers\AsigmaApi\v1\AggregateApiController;

use App\Http\Controllers\Api\v1\Enquiries\CreditReportApiController;
use App\Http\Controllers\Api\v1\Enquiries\NinIDCardValidationApiController;

use App\Http\Controllers\Api\v1\Ogs\ParticipatingInstitutionApiController;
use App\Http\Controllers\Api\v1\Ogs\InstitutionBranchApiController;
use App\Http\Controllers\Api\v1\Ogs\InstituitionStakeholderApiController;
use App\Http\Controllers\Api\v1\Ogs\CreditApplicationApiController;
use App\Http\Controllers\Api\v1\Ogs\BorrowerStakeholderApiController;
use App\Http\Controllers\Api\v1\Ogs\BouncedChequeApiController;
use App\Http\Controllers\Api\v1\Ogs\CollateralCreditGuarantorApiController;
use App\Http\Controllers\Api\v1\Ogs\CollateralMaterialCollateralApiController;
use App\Http\Controllers\Api\v1\Ogs\CreditBorrowerAccountApiController;
use App\Http\Controllers\Api\v1\Ogs\FinancialMalpracticeDataApiController;

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
    'namespace' => 'Api\v1\Mobile',
    'prefix'=>'/v1'
], function () {

    Route::middleware('client_credentials', 'passport.client.set')->group(function(){

        // Route::post('request', [ApiController::class, 'method']);

    }); 

});