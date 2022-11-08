<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dictionary\CodesController;
use App\Http\Controllers\Dictionary\DsmEntityClassificationController;
use App\Http\Controllers\Dictionary\DsmProfileClassificationController;
use App\Http\Controllers\Dictionary\DsmStakeholderCategoryController;
use App\Http\Controllers\Dictionary\DsmEmploymentTypeController;
use App\Http\Controllers\Dictionary\DsmPaymentFrequencyController;
use App\Http\Controllers\Dictionary\DsmBusinessTypeController;
use App\Http\Controllers\Dictionary\DsmCreditAccountTypeController;
use App\Http\Controllers\Dictionary\DsmCreditApplicationStatusController;
use App\Http\Controllers\Dictionary\DsmCreditApplicationRejectionReasonController;
use App\Http\Controllers\Dictionary\DsmOpeningBalanceIndicatorController;
use App\Http\Controllers\Dictionary\DsmCreditAccountAmortizationTypeController;
use App\Http\Controllers\Dictionary\DsmCreditAccountInterestCalculationMethodController;
use App\Http\Controllers\Dictionary\DsmCreditAccountInterestTypeController;
use App\Http\Controllers\Dictionary\DsmCreditAccountStatusController;
use App\Http\Controllers\Dictionary\DsmCreditAccountRiskClassificationController;
use App\Http\Controllers\Dictionary\DsmCreditAccountClosureReasonController;
use App\Http\Controllers\Dictionary\DsmCreditGuarantorsController;
use App\Http\Controllers\Dictionary\DsmCollateralClassificationsController;
use App\Http\Controllers\Dictionary\DsmIndustrySectorCodeController;
use App\Http\Controllers\Dictionary\DsmEconomicSectorController;
use App\Http\Controllers\Dictionary\AdministrationUnitsController;
use App\Http\Controllers\Dictionary\DsmUgandanRegionController;
use App\Http\Controllers\Dictionary\DsmDistrictController;
use App\Http\Controllers\Dictionary\DsmCountyController;
use App\Http\Controllers\Dictionary\DsmSubcountyController;
use App\Http\Controllers\Dictionary\DsmParishController;
use App\Http\Controllers\Dictionary\DsmCountryIsoCodeController;
use App\Http\Controllers\Dictionary\DsmCurrencyController;
use App\Http\Controllers\Dictionary\DsmCurrencyEntityController;
use App\Http\Controllers\Dictionary\DsmCurrencyIsoCodeController;
use App\Http\Controllers\Dictionary\DsmInstitutionTypeController;
use App\Http\Controllers\Dictionary\DsmFraudController;
use App\Http\Controllers\Dictionary\DsmFraudCategoryController;
use App\Http\Controllers\Dictionary\DsmFraudSubCategoryController;
use App\Http\Controllers\Dictionary\DsmSalaryBandController;
use App\Http\Controllers\Dictionary\DsmMaritalStatusController;
use App\Http\Controllers\Dictionary\DsmInternationalDiallingCodeController;
use App\Http\Controllers\Dictionary\DsmChequeAccountBounceReasonController;
use App\Http\Controllers\Dictionary\DsmIdentificationTypeController;
use App\Http\Controllers\Dictionary\DsmFileIdentificationController;
use App\Http\Controllers\Dictionary\DsmSalaryFrequencyController;
use App\Http\Controllers\Dictionary\DsmGenderClassificationController;
use App\Http\Controllers\Dictionary\DsmPremiseOwnershipController;
use App\Http\Controllers\Dictionary\DsmLoanPurposeController;
use App\Http\Controllers\Dictionary\DsmLoanPurposeCategoryController;
use App\Http\Controllers\Dictionary\DsmBranchTypeController;
use App\Http\Controllers\Dictionary\DsmParticipatingInstitutionController;
use App\Http\Controllers\Dictionary\DsmPiBranchesController;

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

	Route::group(['prefix' => 'dictionary', 'as' => 'dictionary.'], function () {
        Route::resource('codes', CodesController::class);
        
        Route::resource('entity-classifications', DsmEntityClassificationController::class); // Entity Classification
        Route::get('entity-classification/list', [DsmEntityClassificationController::class, 'massData'])->name('entity-classifications.list');
        
        Route::resource('profile-classifications', DsmProfileClassificationController::class); // Profile Classification
        Route::get('profile-classification/list', [DsmProfileClassificationController::class, 'massData'])->name('profile-classifications.list');
        
        Route::resource('stakeholder-categories', DsmStakeholderCategoryController::class); // Stakeholder Category
        Route::get('stakeholder-category/list', [DsmStakeholderCategoryController::class, 'massData'])->name('stakeholder-categories.list');
        
        Route::resource('employment-types', DsmEmploymentTypeController::class); // Employment Type
        Route::get('employment-type/list', [DsmEmploymentTypeController::class, 'massData'])->name('employment-types.list');
        
        Route::resource('payment-frequencies', DsmPaymentFrequencyController::class); // Payment Frequency
        Route::get('payment-frequency/list', [DsmPaymentFrequencyController::class, 'massData'])->name('payment-frequencies.list');
        
        Route::resource('business-types', DsmBusinessTypeController::class); // Business Type
        Route::get('business-type/list', [DsmBusinessTypeController::class, 'massData'])->name('business-types.list');
        
        Route::resource('credit-acc-loan-product-types', DsmCreditAccountTypeController::class); // CA/Loan Product Type
        Route::get('credit-acc-loan-product-type/list', [DsmCreditAccountTypeController::class, 'massData'])->name('credit-acc-loan-product-types.list');
        
        Route::resource('credit-app-statuses', DsmCreditApplicationStatusController::class); // CAP Status
        Route::get('credit-app-status/list', [DsmCreditApplicationStatusController::class, 'massData'])->name('credit-app-statuses.list');
       Route::resource('credit-app-rejection-reasons', DsmCreditApplicationRejectionReasonController::class); // CAP Rejec
        Route::get('credit-app-rejection-reason/list', [DsmCreditApplicationRejectionReasonController::class, 'massData'])->name('credit-app-rejection-reasons.list');
        
        Route::resource('opening-balance-indicators', DsmOpeningBalanceIndicatorController::class); // Opening Balance Indicator
        Route::get('opening-balance-indicator/list', [DsmOpeningBalanceIndicatorController::class, 'massData'])->name('opening-balance-indicators.list');
        
        Route::resource('credit-acc-interest-types', DsmCreditAccountInterestTypeController::class); // CA Interest Type
        Route::get('credit-acc-interest-type/list', [DsmCreditAccountInterestTypeController::class, 'massData'])->name('credit-acc-interest-types.list');
        Route::resource('credit-acc-interest-calc-methods', DsmCreditAccountInterestCalculationMethodController::class); // CA Calc Method
        Route::get('credit-acc-interest-calculation-method/list', [DsmCreditAccountInterestCalculationMethodController::class, 'massData'])->name('credit-acc-interest-calc-methods.list');
        
        Route::resource('credit-acc-amortization-types', DsmCreditAccountAmortizationTypeController::class); // CA Amortization Type
        Route::get('credit-acc-amortization-type/list', [DsmCreditAccountAmortizationTypeController::class, 'massData'])->name('credit-acc-amortization-types.list');
        
        Route::resource('credit-acc-statuses', DsmCreditAccountStatusController::class); // CA Status
        Route::get('credit-acc-status/list', [DsmCreditAccountStatusController::class, 'massData'])->name('credit-acc-statuses.list');
        
        Route::resource('credit-acc-risk-classifications', DsmCreditAccountRiskClassificationController::class); // CA Risk Classification
        Route::get('credit-acc-risk-classification/list', [DsmCreditAccountRiskClassificationController::class, 'massData'])->name('credit-acc-risk-classifications.list');
        
        Route::resource('credit-acc-closure-reasons', DsmCreditAccountClosureReasonController::class); // CA Closure Reason
        Route::get('credit-acc-closure-reason/list', [DsmCreditAccountClosureReasonController::class, 'massData'])->name('credit-acc-closure-reasons.list');
        
        Route::resource('collateral-classifications', DsmCollateralClassificationsController::class); // Collateral Classification
        Route::get('collateral-classification/list', [DsmCollateralClassificationsController::class, 'massData'])->name('collateral-classifications.list');
        
        Route::resource('credit-guarantors', DsmCreditGuarantorsController::class); // Credit Guarantor
        Route::get('credit-guarantor/list', [DsmCreditGuarantorsController::class, 'massData'])->name('credit-guarantors.list');
        
        Route::resource('industry-sectors', DsmIndustrySectorCodeController::class); // Industry Sector
        Route::get('industry-sector/list', [DsmIndustrySectorCodeController::class, 'massData'])->name('industry-sectors.list');
        
        Route::resource('economic-sectors', DsmEconomicSectorController::class); // Economic Sector
        Route::get('economic-sector/list', [DsmEconomicSectorController::class, 'massData'])->name('economic-sectors.list');
        
        Route::resource('administration-units', AdministrationUnitsController::class); // Locations/Addresses
        Route::get('administration-unit/list', [AdministrationUnitsController::class, 'massData'])->name('administration-units.list');
        Route::resource('ugandan-regions', DsmUgandanRegionController::class); // Locations/Addresses
        Route::get('ugandan-region/list', [DsmUgandanRegionController::class, 'massData'])->name('ugandan-regions.list');
        Route::resource('districts', DsmDistrictController::class); // Locations/Addresses
        Route::get('district/list', [DsmDistrictController::class, 'massData'])->name('districts.list');
        Route::resource('counties', DsmCountyController::class); // Locations/Addresses
        Route::get('county/list', [DsmCountyController::class, 'massData'])->name('counties.list');
        Route::resource('subcounties', DsmSubcountyController::class); // Locations/Addresses
        Route::get('subcounty/list', [DsmSubcountyController::class, 'massData'])->name('subcounties.list');
        Route::resource('parishes', DsmParishController::class); // Locations/Addresses
        Route::get('parish/list', [DsmParishController::class, 'massData'])->name('parishes.list');
        
        Route::resource('country-iso-codes', DsmCountryIsoCodeController::class); // Country ISO Codes
        Route::get('country-iso-code/list', [DsmCountryIsoCodeController::class, 'massData'])->name('country-iso-codes.list');
        
        Route::resource('currency-iso-codes', DsmCurrencyIsoCodeController::class); // Currency ISO Codes
        Route::get('currency-iso-code/list', [DsmCurrencyIsoCodeController::class, 'massData'])->name('currency-iso-codes.list');
        
        Route::resource('currencies', DsmCurrencyController::class); // Currencies
        Route::get('currency/list', [DsmCurrencyController::class, 'massData'])->name('currencies.list');
        Route::resource('currency-entities', DsmCurrencyEntityController::class); // Currencies
        Route::get('currency-entity/list', [DsmCurrencyEntityController::class, 'massData'])->name('currency-entities.list');
        
        Route::resource('type-of-institutions', DsmInstitutionTypeController::class); // Type of Institution
        Route::get('type-of-institution/list', [DsmInstitutionTypeController::class, 'massData'])->name('type-of-institutions.list');
        
        Route::resource('type-of-branches', DsmBranchTypeController::class); // Type of branches
        Route::get('type-of-branch/list', [DsmBranchTypeController::class, 'massData'])->name('type-of-branches.list');
        
        Route::resource('fraud-classifications', DsmFraudController::class); // Fraud Category
        Route::get('fraud-classification/list', [DsmFraudController::class, 'massData'])->name('fraud-classifications.list');
        Route::resource('fraud-categories', DsmFraudCategoryController::class); // Fraud Category
        Route::get('fraud-category/list', [DsmFraudCategoryController::class, 'massData'])->name('fraud-categories.list');
        Route::resource('fraud-sub-categories', DsmFraudSubCategoryController::class); // Fraud Sub Category
        Route::get('fraud-sub-category/list', [DsmFraudSubCategoryController::class, 'massData'])->name('fraud-sub-categories.list');
        
        Route::resource('salary-bands', DsmSalaryBandController::class); // Salary Bands (UGX)
        Route::get('salary-band/list', [DsmSalaryBandController::class, 'massData'])->name('salary-bands.list');
        
        Route::resource('marital-statuses', DsmMaritalStatusController::class); // Marital Status
        Route::get('marital-status/list', [DsmMaritalStatusController::class, 'massData'])->name('marital-statuses.list');
        
        Route::resource('international-dialling-codes', DsmInternationalDiallingCodeController::class); // International Dialling Codes
        Route::get('international-dialling-code/list', [DsmInternationalDiallingCodeController::class, 'massData'])->name('international-dialling-codes.list');
        
        Route::resource('cheque-account-bounce-reasons', DsmChequeAccountBounceReasonController::class); // Cheque Account Bounce Reason
        Route::get('cheque-account-bounce-reason/list', [DsmChequeAccountBounceReasonController::class, 'massData'])->name('cheque-account-bounce-reasons.list');
        
        Route::resource('salary-frequencies', DsmSalaryFrequencyController::class); // Salary Frequency
        Route::get('salary-frequency/list', [DsmSalaryFrequencyController::class, 'massData'])->name('salary-frequencies.list');
        
        Route::resource('loan-purposes', DsmLoanPurposeController::class); // Loan Purpose
        Route::get('loan-purpose/list', [DsmLoanPurposeController::class, 'massData'])->name('loan-purposes.list');
        Route::resource('loan-purpose-categories', DsmLoanPurposeCategoryController::class); // Loan Purpose
        Route::get('loan-purpose-category/list', [DsmLoanPurposeCategoryController::class, 'massData'])->name('loan-purpose-categories.list');
        
        Route::resource('file-identifications', DsmFileIdentificationController::class); // File Identifier
        Route::get('file-identification/list', [DsmFileIdentificationController::class, 'massData'])->name('file-identifications.list');
        
        Route::resource('identification-types', DsmIdentificationTypeController::class); // Identification Type
        Route::get('identification-type/list', [DsmIdentificationTypeController::class, 'massData'])->name('identification-types.list');
        
        Route::resource('premise-ownerships', DsmPremiseOwnershipController::class); // Premise Ownership
        Route::get('premise-ownership/list', [DsmPremiseOwnershipController::class, 'massData'])->name('premise-ownerships.list');
        
        Route::resource('gender-classifications', DsmGenderClassificationController::class); // Gender Classification
        Route::get('gender-classification/list', [DsmGenderClassificationController::class, 'massData'])->name('gender-classifications.list');
        
        Route::resource('pi-codes', DsmParticipatingInstitutionController::class);
        Route::get('pi-code/list', [DsmParticipatingInstitutionController::class, 'massData'])->name('pi-codes.list');

        Route::resource('branch-codes', DsmPiBranchesController::class);
        Route::get('branch-code/list', [DsmPiBranchesController::class, 'massData'])->name('branch-codes.list');
    });
});
