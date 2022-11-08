<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CreditInformation\BouncedChequeController;
use App\Http\Controllers\CreditInformation\BranchAddressController;
use App\Http\Controllers\CreditInformation\BranchInformationController;
use App\Http\Controllers\CreditInformation\BranchTelephoneNumberController;
use App\Http\Controllers\CreditInformation\CollateralCreditGuarantorController;
use App\Http\Controllers\CreditInformation\CollateralMaterialCollateralController;
use App\Http\Controllers\CreditInformation\CreditApplicationController;
use App\Http\Controllers\CreditInformation\CreditBorrowerAccountController;
use App\Http\Controllers\CreditInformation\CreditBorrowerAccountRepaymentController;
use App\Http\Controllers\CreditInformation\EmploymentInfoController;
use App\Http\Controllers\CreditInformation\FinancialMalpracticeController;
use App\Http\Controllers\CreditInformation\IndividualAddressController;
use App\Http\Controllers\CreditInformation\IndividualAddressesSecController;
use App\Http\Controllers\CreditInformation\IndividualController;
use App\Http\Controllers\CreditInformation\IndividualIdentifierController;
use App\Http\Controllers\CreditInformation\IndividualTelephoneNumberController;
use App\Http\Controllers\CreditInformation\IndividualTelephoneNumbersSecController;
use App\Http\Controllers\CreditInformation\InstitutionAddressController;
use App\Http\Controllers\CreditInformation\InstitutionTelephoneNumberController;
use App\Http\Controllers\CreditInformation\NonIndividualAddressController;
use App\Http\Controllers\CreditInformation\NonIndividualAddressSecController;
use App\Http\Controllers\CreditInformation\NonIndividualController;
use App\Http\Controllers\CreditInformation\NonIndividualIdentifierController;
use App\Http\Controllers\CreditInformation\NonIndividualTelephoneNumberController;
use App\Http\Controllers\CreditInformation\NonIndividualTelephoneNumbersSecController;
use App\Http\Controllers\CreditInformation\PiInformationController;
use App\Http\Controllers\CreditInformation\PiStakeholderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'otp_verification']], function() {

    Route::group(['prefix' => 'credit-data', 'as' => 'credit-data.'], function () {
        Route::resource('bounced-cheques', BouncedChequeController::class);
        Route::get('bounced-cheque/list', [BouncedChequeController::class, 'massData'])->name('bounced-cheques.list');
    });
    
});
