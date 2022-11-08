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
    'namespace' => 'Api\v1\Enquiries',
    'prefix'=>'/v1'
], function () {

    Route::middleware('client_credentials', 'passport.client.set')->group(function(){

        Route::post('credit-enquiries/consent-request', [CreditReportApiController::class, 'consentRequest']);
        Route::post('credit-enquiries/consent-submission', [CreditReportApiController::class, 'consentSubmit']);

        Route::post('credit-enquiries/request/by-identifier', [CreditReportApiController::class, 'searchByIdentifier']);

        Route::post('credit-enquiries/individual/credit-report', [CreditReportApiController::class, 'individualReport']);
        Route::post('credit-enquiries/non-individual/credit-report', [CreditReportApiController::class, 'nonIndividualReport']);     

        Route::post('validate-personal-info', [NinIDCardValidationApiController::class, 'store']);
        Route::get('personal-info-validations', [NinIDCardValidationApiController::class, 'index']);
        Route::get('personal-info-validation/{validation}', [NinIDCardValidationApiController::class, 'show']);
        Route::get('personal-info-validation-report/{validation}', [NinIDCardValidationApiController::class, 'report']);

        // Route::post('validate_phone', [PhoneValidationMobileController::class, 'store']);
        // Route::get('phone_validations', [PhoneValidationMobileController::class, 'index']);
        // Route::get('phone_validation/{validation}', [PhoneValidationMobileController::class, 'show']);
        // Route::get('phone_validation/report/{validation}', [PhoneValidationMobileController::class, 'report']);

    }); 

});

Route::group([
    'namespace' => 'AsigmaApi\v1',
    'prefix'=>'/v1'
], function () {

    Route::middleware('client_credentials')->group(function(){

        Route::get('fetch-loans', [CreditAccountApiController::class, 'index']);
        Route::post('loan-notifications', [CreditAccountApiController::class, 'confirm']);        
        Route::get('fetch-repayments', [RepaymentsApiController::class, 'index']);
        Route::post('loan-repayments', [RepaymentsApiController::class, 'repayments']);
        Route::get('aggregated-data', [AggregateApiController::class, 'index']);
        Route::post('aggregated-data/institution', [AggregateApiController::class, 'institution']);
        Route::get('reset-update-status', [RepaymentsApiController::class, 'reset_updates']);
        Route::get('update-num-of-repayments', [RepaymentsApiController::class, 'update_num']);

    }); 

});

Route::group([
    'namespace' => 'Api\v1',
    'prefix'=>'/v1/ogs/'
], function () {

    Route::middleware('client_credentials')->group(function(){

        Route::post('participating-institution', [ParticipatingInstitutionApiController::class, 'store'])->name("participating-sacco");
        Route::post('branch', [InstitutionBranchApiController::class, 'store'])->name("sacco-branch");
        Route::post('credit-application', [CreditApplicationApiController::class, 'store']);
        Route::post('credit-borrower-account', [CreditBorrowerAccountApiController::class, 'store']);
        Route::post('borrower-stakeholder', [BorrowerStakeholderApiController::class, 'store']);
        Route::post('collateral-material-collateral', [CollateralMaterialCollateralApiController::class, 'store']);
        Route::post('collateral-credit-guarantor', [CollateralCreditGuarantorApiController::class, 'store']);
        Route::post('financial-malpractice', [FinancialMalpracticeDataApiController::class, 'store']);
        // Route::post('participating-sacco-stakeholder', [InstituitionStakeholderApiController::class, 'store']);
        // Route::post('bounced-cheque', [BouncedChequeApiController::class, 'store']);

    }); 

});