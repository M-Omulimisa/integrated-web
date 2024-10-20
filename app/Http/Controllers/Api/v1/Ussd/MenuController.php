<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\Ussd\MenuFunctions;
use App\Jobs\SendUssdAdvisoryMessage;
use App\Models\Ussd\UssdLanguage;
use App\Models\Ussd\UssdSession;
use App\Models\ProductCategory;
use Carbon\Carbon;
use App\Models\Ussd\UssdSessionData;
use App\Models\Market\MarketSubscription;

class MenuController extends Controller
{
    protected $_reference;

    public function __construct(MenuFunctions $menu_helper)
    {
        $this->menu_helper  = $menu_helper;
    }

    /**
     * Receiving parameters from Africa Is Talking API
     * @return  - String request from user or - String closing ussd session
     */
    public function index(Request $request)
    {
        //sent variables
        // Log::info(['YoUssdData' => $request->all()]);

        $sessionId          = $request->transactionId;
        $transactionTime    = $request->transactionTime;
        $phoneNumber        = $request->msisdn;
        $insuree            = $phoneNumber;
        $serviceCode        = $request->ussdServiceCode;
        $ussdRequestString  = $request->ussdRequestString;
        $response           = $request->response;

        $input_text   = $ussdRequestString; //end($text_chain); //last user input
        $field        = null; //column in subscription table
        $display_main_menu = false;

        //get the last menu for this session
        $last_menu = $this->menu_helper->getLastMenu($sessionId, $phoneNumber);

        $main_menu = "Welcome to M-Omulimisa\n";
        $main_menu .= "1) Agriculture Insurance \n";
        $main_menu .= "2) Market Information \n";
        $main_menu .= "3) Weather Information\n";
        $main_menu .= "4) Farmers Marketplace\n";
        $main_menu .= "5) View my Digisave Profile\n";
        $main_menu .= "6) Contact Us Directly";

        $advisory_option_menu = "Select option\n";
        $advisory_option_menu .= "1) Advisory Tips\n";
        $advisory_option_menu .= "2) Advisory Tip Evaluation";

        $advisory_languages_menu  = "Select language!\n";
        $advisory_languages_menu .= "1) English\n";
        $advisory_languages_menu .= "2) Acholi & Lango\n";

        $languages_menu  = "Select language!\n";
        $languages_menu .= "1) English\n";
        $languages_menu .= "2) Acholi & Lango\n";
        $languages_menu .= "3) Lango\n";
        $languages_menu .= "4) Luganda\n";
        $languages_menu .= "5) Runyakitara";

        $subscriber     = "Subscribe for\n";
        $subscriber     .= "1) Myself\n";
        $subscriber     .= "2) Another person";

        $enter_phone = "Enter phone e.g 07XXXXXXXX";
        $invalid_phone = "Invalid phone number";

        // "Enter no. of acres\n";
        $acreage = "How many acres do you want to insure?\n";

        $weather_period = "Subscription Period\n";
        $weather_period .= "1) Weekly\n";
        $weather_period .= "2) Monthly\n";
        $weather_period .= "3) Annual\n";

        $insure_more = "Want to insure another crop?\n";
        $insure_more .= "1) No\n";
        $insure_more .= "2) Yes";

        $sum_insured = 0;
        $premium = 0;

        $insurance_coverage = "Select insurance coverage\n";
        $insurance_coverage .= "1) Half coverage (45%)\n";
        $insurance_coverage .= "2) Full coverage (90%)";

        $referee  = "Were you referred by an Agent?\n";
        $referee .= "1) No\n";
        $referee .= "2) Yes";

        //new farmers market section
        $new_user_welcome_message = "Welcome to Farmer Market. Choose an option:\n";
        $new_user_welcome_message .= "1. Buyer\n";
        $new_user_welcome_message .= "2. Seller\n";
        $new_user_welcome_message .= "3. Service Provider\n";

        $new_user_name = "Hi there. What are your full names?";

        $new_user_gender = "What is your gender (Choose 1 or 2)?\n";
        $new_user_gender .= "1. Male\n";
        $new_user_gender .= "2. Female\n";
        $new_user_gender .= "0. Go back\n";

        $new_user_age = "How old are you (in years)?\n";

        $new_user_district = "Enter District e.g Kampala";

        if ($last_menu == null) {
            $response  = $main_menu;
            $action = "request";
            $current_menu = "main_menu";
        } elseif ($last_menu == "main_menu") {
            $action = "request";

            if ($input_text == '1') {
                $response       = $subscriber;
                $current_menu   = "insurance_phone_option";
                $module         = 'insurance';
            } elseif ($input_text == '2') {
                $response       = $subscriber;
                $current_menu   = "market_phone_option";
                $module         = 'market';
            } elseif ($input_text == '3') {
                // Ask language Weather information
                $response       = $subscriber;
                $current_menu   = "weather_phone_option";
                $module         = 'weather';
            } elseif ($input_text == '4') {
                //print found user
                $foundUserData = $this->menu_helper->getMarketPlaceUserData($phoneNumber);

                if ($foundUserData) {
                    if ($foundUserData->done_with_ussd_farming_onboarding == "Yes") {
                        //TODO: Get approved categories and display them
                        $categories = ProductCategory::where('show_in_ussd', "Yes")->get();
                        $optionMappings = [];

                        $list = "Chose a category: \n";
                        if (count($categories) > 0) {
                            $count = 0;
                            foreach ($categories as $language) {
                                $list .= (++$count) . ") " . ucwords(strtolower($language->category)) . "\n";
                                $optionMappings[$count] = $language->id;
                            }
                        }

                        $list .= "0) Go back\n";

                        $this->menu_helper->saveToField($sessionId, $phoneNumber, "farmer_market_category_options", json_encode($optionMappings));

                        $response = $list;

                        $current_menu   = "farmer_market_categories";
                    } else {
                        $response = $new_user_welcome_message;
                        $current_menu   = "farmer_market_first_menu";
                    }
                } else {
                    $response = $new_user_welcome_message;
                    $current_menu   = "farmer_market_first_menu";
                }

                $action         = "request";
                $module         = 'farmers_market';
            } elseif ($input_text == '5') {
                //Check if the person has a digidave account via api call to bannaddda's api stuff

                $response = "Welcome to DigiSave. Please select an option.\n";
                $response .= "1) My personal account\n";
                $response .= "2) My group\n";

                $module         = 'digisave';
                $current_menu   = "digisave_menu_choice";
                $action         = "request";
            } elseif ($input_text == '6') {
                //print found user
                $response = "You can call us on 0200 904 415";
                $current_menu   = "call_us_now";

                $action         = "end";
                $module         = 'contact_us';
            } else {
                $action         = "end";
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input";
            }

            //create record
            if (isset($module)) $this->menu_helper->startMenu($sessionId, $phoneNumber, $module);
        }

        /**********START DIGISAVE*******************************/
        //personal results
        elseif ($last_menu == "digisave_menu_choice" && $input_text == '1') {
            $hasDigisaveAccount = $this->menu_helper->checkUserDigisaveAccount("+" . $phoneNumber);

            if ($hasDigisaveAccount["code"] == 0) {
                $response = "You do not have a DigiSave account. Kindly contact us on +256 776 035 192.";

                $current_menu   = "digisave_no_account";
                $module         = 'digisave';
                $action         = "end";
            } else {
                $response = "Hi there. Here's your DigiSave account as at " . Carbon::now()->format('M d, Y') . "\n";
                $response .= "Savings: " . number_format($hasDigisaveAccount["data"]["member"]["balance"]) . " UGX\n";
                $response .= "Loan: " .  number_format($hasDigisaveAccount["data"]["member"]["loan_amount"]) . " UGX\n";
                $response .= "Outstanding Loan: " . number_format($hasDigisaveAccount["data"]["member"]["outstanding_loan"]) . " UGX\n";
                $response .= "Profit: " . number_format($hasDigisaveAccount["data"]["member"]["member_profit"], 0) . " UGX\n";

                $current_menu   = "digisave_profile";
                $module         = 'digisave';
                $action         = "end";
            }
        }

        //group results
        elseif ($last_menu == "digisave_menu_choice" && $input_text == '2') {
            $hasDigisaveAccount = $this->menu_helper->checkUserDigisaveAccount("+" . $phoneNumber);

            if ($hasDigisaveAccount["code"] == 0) {
                $response = "You do not have a DigiSave account. Kindly contact us on +256 776 035 192.";

                $current_menu   = "digisave_no_account";
                $module         = 'digisave';
                $action         = "end";
            } else {
                $response = "Hi there. Here's your Group account as at " . Carbon::now()->format('M d, Y') . "\n";
                $response .= "Savings: " . number_format($hasDigisaveAccount["data"]["group"]["savings"]) . " UGX\n";
                $response .= "Loan: " .  number_format($hasDigisaveAccount["data"]["group"]["loan_amount"]) . " UGX\n";
                $response .= "Outstanding Loan: " . number_format($hasDigisaveAccount["data"]["group"]["outstanding_loan"]) . " UGX\n";
                $response .= "Profit: " . number_format($hasDigisaveAccount["data"]["group"]["group_profit"], 0) . " UGX\n";

                $current_menu   = "digisave_profile";
                $module         = 'digisave';
                $action         = "end";
            }
        }

        /******************* START INSURANCE *******************/
        //person choses 1 and its time to chose location
        elseif ($last_menu == "insurance_phone_option" && $input_text == '1' || $last_menu == "insurance_phone" || $last_menu == 'insurance_subcounty' && $input_text == '0') {
            $action         = "request";

            //invalid phone number entered, yet yo ass chose to insure for another
            if ($last_menu == "insurance_phone" && !$this->menu_helper->isLocalPhoneValid($input_text, '256')) {
                $response       = $invalid_phone . "\n";
                $response       .= $enter_phone;
                $current_menu   = "insurance_phone";
            }

            //correct phone number. Time to get region
            else {
                if ($last_menu != "insurance_phone") {
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_subscrption_for', 'self');
                    $insuree = $this->menu_helper->formatPhoneNumbers($phoneNumber, '256', 'international');
                } else {
                    $insuree = $this->menu_helper->formatPhoneNumbers($input_text, '256', 'international');
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_subscrption_for', 'another');
                }

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_subscriber', $insuree);

                $response       = "Select a region:\n";
                $response       .= $this->menu_helper->getInsuranceRegionList($sessionId, $phoneNumber,);
                $current_menu   = "insurance_region_list";
            }
        }

        //enter another person's phone number
        elseif ($last_menu == "insurance_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter phone e.g 07XXXXXXXX";
            $current_menu   = "insurance_phone";
            $field          = "insurance_subscrption_for";
        }

        // invalid input
        elseif ($last_menu == "insurance_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input";
        }

        //chose crop
        else if ($last_menu == "insurance_region_list") {
            $field          = "insurance_region_id";
            $input_text     = $this->menu_helper->getSelectedRegionID($phoneNumber, $sessionId, $input_text);

            $action         = "request";
            $response       = "Which crop do you want to insure?\n";
            $response       .= $this->menu_helper->regionItemList($sessionId, $phoneNumber, $input_text);
            $current_menu   = "insurance_item";
        }

        //chose season
        else if ($last_menu == "insurance_item") {
            $field          = "insurance_enterprise_id";
            $input_text     = $this->menu_helper->getSelectedItemID($phoneNumber, $sessionId, $input_text);

            $action         = "request";
            $response       = "For which season:\n";
            $response       .= $this->menu_helper->insuranceSeasonList();
            $current_menu   = "insurance_season_list";
        }

        //chose acreage
        elseif ($last_menu == "insurance_season_list") {
            $action         = "request";

            //if ($this->menu_helper->checkIfSeasonItemIsValid($input_text)) {
            $field          = "insurance_season_id";
            $input_text     = $this->menu_helper->getSelectedSeasonID($input_text);

            $response       = $acreage;
            $current_menu   = "insurance_acreage";
            // }
            // else{
            //     $response       = "Wrong Item!\n";
            //     $response       .= "Select item to insure:\n";
            //     $response       .= $this->menu_helper->seasonItemList($seasonId);
            //     $current_menu   = "insurance_item";
            // }
        } elseif ($last_menu == "insurance_acreage") {
            $action    = "request";
            if (is_numeric($input_text) && $input_text > 0) {

                $selected_acreage = $this->menu_helper->getAcerage($input_text);

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_acreage', $selected_acreage);

                $response       = $insurance_coverage;
                $current_menu   = "insurance_coverage";
                $field          = "insurance_acreage";
            } else {
                $response       = "Wrong input!\n";
                $response       .= $acreage;
                $current_menu   = "insurance_acreage";
            }
        }

        //chose coverage
        else if ($last_menu == 'insurance_coverage') {
            $enterprise_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_enterprise_id');
            $selected_acreage  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_acreage');
            $sum_insured    = $this->menu_helper->getPremiumOptionDetails($enterprise_id, 'sum_insured_per_acre');
            $markup    = $this->menu_helper->getMarkup($enterprise_id, 'markup');
            $premiumPercentage        = $this->menu_helper->getPremiumOptionDetails($enterprise_id, 'premium_per_acre');

            $premium        = (($premiumPercentage / 100) * $selected_acreage * $sum_insured);

            if ($input_text == 1) {
                info($sum_insured);
                info($selected_acreage);
                info(($sum_insured / 2) * $selected_acreage);

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_sum_insured', (($sum_insured / 2) * $selected_acreage));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_premium', (($premium / 2) + $markup));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'markup',  $markup);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_coverage', 'half');

                $action = 'request';
                $response = $this->menu_helper->getInsuranceConfirmation($sessionId, $phoneNumber);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                $response .= "\n1) Yes\n";
                $response .= "2) No";
                $current_menu   = "insurance_confirmation";
            } else if ($input_text == 2) {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_sum_insured', ($sum_insured * $selected_acreage));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_premium', ($premium  + $markup));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_coverage', 'full');
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'markup',  $markup);

                $action = 'request';
                $response = $this->menu_helper->getInsuranceConfirmation($sessionId, $phoneNumber);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                $response .= "\n1) Yes\n";
                $response .= "2) No";
                $current_menu   = "insurance_confirmation";
            } else {
                $response       = "Wrong input!\n";
                $response       .= $acreage;
                $current_menu   = "insurance_acreage";
            }
        }

        //finalizing insurance
        elseif ($last_menu == "insurance_confirmation") {
            $action         = "end";

            //want to proceed
            if ($input_text == '1') {
                //success
                if ($this->menu_helper->completeInsuranceSubscription($sessionId, $phoneNumber)) {
                    $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_subscriber');
                    $response       = "Thank you for subscribing.\n";
                    $response       .= "Check " . $phone . " to approve the payment\n";
                }
                //failed
                else {
                    $response = "Subscription was unsuccessful. Please try again";
                }

                $current_menu   = "insurance_confirmed";
                $field          = "insurance_confirmation";
            }

            //choses not to proceed
            elseif ($input_text == '2') {
                $response       = "Transaction has been cancelled";
                $current_menu   = "insurance_cancelled";
                // $input_text     = "CANCELLED";              
            }

            //invalid entry
            else {
                $action         = "request";
                $response       = "Invalid input!\n";
                $response       .= $this->menu_helper->sessionData($sessionId, $phoneNumber, 'confirmation_message');
                $response       .= "\n1) Yes\n";
                $response       .= "2) No";
                $current_menu   = "insurance_confirmation";
            }
        }

        /******************* START MARKET *******************/
        elseif ($last_menu == "market_phone_option" && $input_text == '1' || $last_menu == "market_phone") {
            $action         = "request";

            //phone number
            if ($last_menu == "market_phone" && !$this->menu_helper->isLocalPhoneValid($input_text, '256')) {
                $response       = $invalid_phone . "\n";
                $response       .= $enter_phone;
                $current_menu   = "market_phone";
            } else {
                //select package
                $packages = $this->menu_helper->getPackages();
                $response = "Select package:\n";
                foreach ($packages as $package) {
                    if (!empty($package->ussd_name)) {
                        $response .= $package->menu . ") " . $package->ussd_name . "\n";
                    } else {
                        $entNames = $package->ents->pluck('name')->implode(', ');
                        $response .= $package->menu . ") " . $entNames . "\n";
                    }
                }

                $current_menu   = "market_package";

                if ($last_menu != "market_phone") {
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_subscrption_for', 'self');
                    $input_text = $phoneNumber;
                } else {
                    $input_text = $this->menu_helper->formatPhoneNumbers($phoneNumber, '256', 'international');
                }

                $field          = 'market_subscriber';
            }
        } elseif ($last_menu == "market_phone_option" && $input_text == '2') {
            //subscribe for another person
            $action         = "request";
            $response       = $enter_phone;
            $current_menu   = "market_phone";

            $field          = 'market_subscrption_for';
            $input_text     = 'another';
        } elseif ($last_menu == "market_phone_option") {
            $action         = "request";
            $response       = "Invalid input!\n";
            $response       .= $subscriber;
            $current_menu   = "market_phone_option";
            //TOASK: What's the use of this? There's no regions anywhere
        } elseif ($last_menu == "market_region") {
            $region = $this->menu_helper->getSelectedRegion($input_text);
            $input_text = $region->name ?? null;

            if ($this->menu_helper->checkIfRegionIsValid($input_text)) {
                $action         = "request";
                $response       = $input_text . "\n";
                $response       = "Select language:\n";
                $response       .= $this->menu_helper->getRegionLanguageList($region->id);
                $current_menu   = "market_languages";

                $field = "market_region";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_region_id', $region->id);
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Region\n";
                $response       .= $this->menu_helper->getRegionList();
                // $response       .= "0) Back\n";
                $current_menu   = "market_region";
            }
        } elseif ($last_menu == "market_languages") {
            $region_id = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_region_id');
            $package_id   = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');
            $language = $this->menu_helper->getSelectedLanguage($input_text, $sessionId, $phoneNumber);
            $input_text = $language->name ?? null;

            if ($this->menu_helper->checkIfLanguageIsValid($input_text)) {
                $action         = "request";
                $response       = $input_text . "\n";
                $response       = "Select frequency:\n";
                $response       .= $this->menu_helper->getPackageFrequencies($package_id);
                $current_menu   = "market_frequency";

                $field = "market_language";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_language_id', $language->id);
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Region\n";
                $response       .= "Select language:\n";
                $response       .= $this->menu_helper->getRegionLanguageList($region_id);
                $current_menu   = "market_languages";
            }
        } elseif ($last_menu == "market_package") {
            $action         = "request";

            $package = $this->menu_helper->getSelectedPackage($input_text);
            $count = 0;

            if ($package) {
                $response       = "Select language:\n";
                $languages      = $this->menu_helper->getLanguages("market");
                $optionMappings = [];

                foreach ($languages as $language) {
                    $response .= (++$count) . ") " . $language->name . "\n";
                    $optionMappings[$count] = $language->id;
                }

                $current_menu   = "market_languages";

                $input_text = $package->name;
                // $field          = 'market_package';
                $this->menu_helper->saveToField($sessionId, $phoneNumber, "option_mappings", $optionMappings);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_package_id', $package->id);
            } else {
                $response       = "Invalid input!\n";
                $packages = $this->menu_helper->getPackages();

                foreach ($packages as $package) {
                    $response .= $package->menu . ") " . $package->name . "\n";
                }

                $current_menu   = "market_package";
            }
        } elseif ($last_menu == "market_frequency") {
            $action       = "request";
            $package_id   = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');
            $frequency    = $this->menu_helper->getSelectedPackageFrequency($package_id, $input_text);
            $input_text = $frequency->frequency ?? null;
            $id = $frequency->id ?? null;

            if ($this->menu_helper->isPackageFrequencyValid($package_id, $id)) {

                if (strcasecmp($input_text, "trial") == 0) {
                    $response = "You are subscribing for the trial package lasting 30 days\n";
                    $response .= "1) Yes\n";
                    $response .= "2) Cancel\n";
                    $current_menu   = "market_period";
                } else {
                    $response       = "How many " . str_replace('ly', 's', $input_text) . "?";
                    $current_menu   = "market_period";
                }

                $field = 'market_frequency';
            } else {
                $response       = "Invalid input! Select frequency:\n";
                $response       .= $this->menu_helper->getPackageFrequencies($package_id);
                $current_menu   = "market_frequency";
            }
        } elseif ($last_menu == "market_period" || $last_menu == "market_confirmation" && $input_text != "1" && $input_text != "2") {
            $action    = "request";
            $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_frequency');
            $_frequency = str_replace('ly', 's', $frequency);

            // Back to this step -- Retrieve previous input
            if ($last_menu == "market_confirmation") $input_text = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_frequency_count');

            if (strcasecmp($_frequency, "trial") == 0) {
                $check_for_previous_subscription = MarketSubscription::where('phone', $phoneNumber)->where('frequency', 'Trial')->first();

                if ($check_for_previous_subscription === null) {

                    $trial_package_frequency = 14;

                    $package_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');
                    $enterprises = $this->menu_helper->getPackageEnterprises($package_id);
                    $cost        = 0;
                    $currency    = 'UGX';

                    $response  = "Subscribing for " . $enterprises . " market info for " . $_frequency . " at " . $currency . ' ' . number_format($cost * $input_text);
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                    $response .= "\n1) Confirm\n";
                    $response .= "2) Cancel";
                    $current_menu   = "market_confirmation";

                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_cost', ($cost * $input_text));

                    $field = 'market_frequency_count';
                } else {
                    $response       = "You already used the your trial package period. Thank you";
                    $current_menu   = "market_cancelled";
                }
            } else {
                if (!is_numeric($input_text) && $input_text >= 0) {
                    $response       = "Invalid input!\n";
                    $response       .= "Enter number of " . $_frequency;
                    $current_menu   = "market_period";
                } else {
                    $package_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');
                    info($package_id);

                    $enterprises = $this->menu_helper->getPackageEnterprises($package_id);
                    $cost        = $this->menu_helper->getPackageCost($package_id, $frequency, $input_text);
                    $currency    = 'UGX';

                    if (!is_null($cost)) {
                        // code...
                        $response  = "Subscribing for " . $enterprises . " market info for " . $input_text . $_frequency . " at " . $currency . '' . number_format($cost * $input_text);
                        $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                        $response .= "\n1) Confirm\n";
                        $response .= "2) Cancel";
                        $current_menu   = "market_confirmation";

                        $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_cost', ($cost * $input_text));

                        $field = 'market_frequency_count';
                    } else {
                        $action         = "end";
                        $response       = "Selected package has no pricing";
                        $current_menu   = "market_cost_error";
                    }
                }
            }
        } elseif ($last_menu == "market_confirmation") {
            // check if crop is valid
            $action         = "end";
            if ($input_text == '1') {

                $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_frequency');

                if (strcasecmp($frequency, "trial") == 0) {
                    if ($this->menu_helper->completeTrialMarketSubscription($sessionId, $phoneNumber)) {
                        $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_subscriber');
                        $response       = "Thank you for subscribing.\n";
                    } else {
                        $response = "Subscription was unsuccessful. Please try again";
                    }
                } else {
                    if ($this->menu_helper->completeMarketSubscription($sessionId, $phoneNumber)) {
                        $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_subscriber');
                        $response       = "Thank you for subscribing.\n";
                        $response       .= "Check " . $phone . " to approve the payment\n";
                    } else {
                        $response = "Subscription was unsuccessful. Please try again";
                    }
                }

                $current_menu   = "market_confirmed";
                $field = 'market_confirmation';
            } elseif ($input_text == '2') {
                $response       = "Transaction has been cancelled";
                $current_menu   = "market_cancelled";
                // $input_text     = "CANCELLED";              
            } else {
                $action         = "request";
                $response       = "Invalid input!\n";
                $response       .= $this->menu_helper->sessionData($sessionId, $phoneNumber, 'confirmation_message');
                $response       .= "\n1) Confirm\n";
                $response       .= "2) Cancel";
                $current_menu   = "market_confirmation";
            }
        }

        /******************* START WEATHER *******************/
        elseif ($last_menu == "weather_phone_option" && $input_text == '1' || $last_menu == "weather_phone") {
            $action         = "request";

            if ($last_menu == "weather_phone" && !$this->menu_helper->isLocalPhoneValid($input_text, '256')) {
                $response       = $invalid_phone . "\n";
                $response       .= $enter_phone;
                $current_menu   = "weather_phone";
            } else {
                $response       = "Enter District e.g Kampala";
                $current_menu   = "weather_district";

                if ($last_menu != "weather_phone") {
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_subscrption_for', 'self');
                    $input_text = $phoneNumber;
                } else {
                    $input_text = $this->menu_helper->formatPhoneNumbers($phoneNumber, '256', 'international');
                }

                if ($input_text != '0') $field = 'weather_subscriber';
            }
        } elseif ($last_menu == "weather_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter phone e.g 07XXXXXXXX";
            $current_menu   = "weather_phone";
            $field          = "weather_subscrption_for";
            $input_text     = "another";
        } elseif ($last_menu == "weather_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input";
        } elseif ($last_menu == "weather_district") {
            $district = $this->menu_helper->getMostSimilarDistrict($input_text, "Uganda");
            $input_text = $district->name ?? null;

            if ($this->menu_helper->checkIfDistrictIsValid($input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text . "\n";
                $response       .= "Select Subcounty:\n";

                $districtId = $this->menu_helper->getDistrict($district->id, 'id');

                $response       .= $this->menu_helper->getSubcountyList($districtId);
                // $response       .= "0) Back\n";
                $current_menu   = "weather_subcounty";

                $field = "weather_district";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_district_id', $districtId);
            } else {
                $action         = "request";
                $response       = "Wrong District!\n";
                $response       .= "Enter District e.g Kampala";
                $current_menu   = "weather_district";
            }
        } elseif ($last_menu == "weather_subcounty") {
            $districtId = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_district_id');
            $subcounty = $this->menu_helper->getSelectedSubcounty($input_text, $districtId);
            $input_text = $subcounty->name ?? null;

            if ($this->menu_helper->checkIfSubcountyIsValid($districtId, $input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text . "\n";
                $response       .= "Select Parish:\n";
                $response       .= $this->menu_helper->getParishList($subcounty->id);
                // $response       .= "0) Back\n";
                $current_menu   = "weather_parish";

                $field = "weather_subcounty";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_subcounty_id', $subcounty->id);
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Subcounty\n";
                $response       .= $this->menu_helper->getSubcountyList($districtId);
                // $response       .= "0) Back\n";
                $current_menu   = "weather_subcounty";
            }
        } elseif ($last_menu == "weather_parish") {
            // check if parish is valid
            $action         = "request";
            $response       = $languages_menu;
            $current_menu   = "weather_languages_menu";

            $subcountyId = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_subcounty_id');
            $parish = $this->menu_helper->getSelectedParish($input_text, $subcountyId);
            $input_text = $parish->name ?? null;

            if ($this->menu_helper->checkIfParishIsValid($subcountyId, $input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text . "\n";
                $response       .= $weather_period;
                $current_menu   = "weather_period";

                $field = "weather_parish";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_parish_id', $parish->id);
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Parish\n";
                $response       .= $this->menu_helper->getParishList($subcountyId);
                $response       .= "0) Back\n";
                $current_menu   = "weather_parish";
            }
        } elseif ($last_menu == "weather_period") {
            if ($input_text == "1" || $input_text == "2" || $input_text == "3") {

                $details = $this->menu_helper->getWeatherPeriodDetails($input_text);

                $action         = "request";
                $response       = "How many " . $details->period . "s?";
                $current_menu   = "weather_frequency";

                $input_text     = $details->frequency;
                $field          = "weather_frequency";
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= $weather_period;
                // $response       .= "0) Back\n";
                $current_menu   = "weather_period";
            }
        } elseif ($last_menu == "weather_frequency") {
            if (is_numeric($input_text) && $input_text > 0) {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_frequency_count', $input_text);
                $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_frequency');

                $details = $this->menu_helper->getWeatherPeriodDetails($frequency, $input_text);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'weather_amount', $details->cost);

                $action         = "request";
                $response       = "Which language would you prefer?\n";
                $languages      = $this->menu_helper->getLanguages("weather");
                $optionMappings = [];

                $count = 0;

                foreach ($languages as $language) {
                    $response .= (++$count) . ") " . $language->name . "\n";
                    $optionMappings[$count] = $language->id;
                }

                $this->menu_helper->saveToField($sessionId, $phoneNumber, "option_mappings", $optionMappings);
                $current_menu   = "weather_language";
            } else {
                $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_frequency');
                $weather = $this->menu_helper->getWeatherPeriodDetails($frequency);

                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "How many " . $weather->period . "s?";
                $current_menu   = "weather_frequency";
            }
        } elseif ($last_menu == "weather_language") {
            if (is_numeric($input_text) && $input_text > 0) {
                $input_text  = $this->menu_helper->getSelectedRegionID($phoneNumber, $sessionId, $input_text);

                $district = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_district');
                $subcounty = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_subcounty');
                $parish = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_parish');

                $periodCount = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_frequency_count');

                $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_frequency');
                $details = $this->menu_helper->getWeatherPeriodDetails($frequency, $periodCount);

                $action    = "request";
                $response  = "Subscribing for weather info in " . $parish . ", " . $subcounty . ", " . $district . " for " . $periodCount . "" . $details->period . "s at ugx " . $details->cost . ".\n";
                $response .= "1) Confirm\n";
                $response .= "2) Cancel";
                $current_menu   = "weather_confirmation";
                $field          = "weather_language_id";
            } else {
                $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_frequency');
                $weather = $this->menu_helper->getWeatherPeriodDetails($frequency);

                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Chose a language please\n";
                $languages      = $this->menu_helper->getLanguages("weather");
                $optionMappings = [];

                $count = 0;

                foreach ($languages as $language) {
                    $response .= (++$count) . ") " . $language->name . "\n";
                    $optionMappings[$count] = $language->id;
                }

                $this->menu_helper->saveToField($sessionId, $phoneNumber, "option_mappings", $optionMappings);

                $current_menu   = "weather_language";
            }
        } elseif ($last_menu == "weather_confirmation") {
            // check if crop is valid
            $action         = "end";

            if ($input_text == '1') {
                if ($this->menu_helper->completeWeatherSubscription($sessionId, $phoneNumber)) {
                    $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'weather_subscriber');
                    $response       = "Thank you for subscribing.\n";
                    $response       .= "Check " . $phone . " to approve the payment\n";
                } else {
                    $response = "Subscription was unsuccessful. Please try again";
                }

                $current_menu   = "weather_confirmed";
                $field = 'weather_confirmation';
            } elseif ($input_text == '2') {
                $response       = "Transaction has been cancelled";
                $current_menu   = "weather_cancelled";
                // $input_text     = "CANCELLED";              
            } else {
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input";
            }
        }

        /******************* START NEW FARMERS MARKET SHIT *******************/
        //new user stuff
        elseif ($last_menu == "farmer_market_first_menu") {
            $user_type = "";

            if ($input_text == "1") {
                $user_type = "buyer";
            } elseif ($input_text == "2") {
                $user_type = "seller";
            } else {
                $user_type = "service_provider";
            }

            //User does not exist in DB
            if ($input_text == "1" || $input_text == "2" || $input_text == "3") {

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_user_type',  $user_type);
                $districtName = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'farmer_market_user_type');

                $response = $new_user_name;
                $current_menu   = "new_user_name";
            } else {
                $response = "Invalid option chosen. Please press (1) if you are a buyer, (2) If you are a seller and (3) if you are a service provider.";
                $current_menu   = "farmer_market_first_menu";
            }

            $action = "request";
        } elseif ($last_menu == "new_user_name") {
            $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_user_name',  $input_text);

            $response = $new_user_gender;
            $current_menu   = "new_user_gender";

            $action = "request";
        } elseif ($last_menu == "new_user_gender") {
            $user_gender = "";

            if ($input_text == "1") {
                $user_gender = "male";
            } elseif ($input_text == "2") {
                $user_gender = "female";
            }

            //User does not exist in DB
            if ($input_text == "1" || $input_text == "2") {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_user_gender',  $user_gender);

                $response = $new_user_age;
                $current_menu   = "new_user_age";
            } elseif ($input_text == "0") {
                $response = $new_user_name;
                $current_menu   = "new_user_name";
            } else {
                $response = "Invalid option chosen. Please press (1) if you are male and (2) if you are female.";
                $current_menu   = "new_user_gender";
            }

            $action = "request";
        } elseif ($last_menu == "new_user_age") {
            if (!is_numeric($input_text)) {
                $response = "Invalid input. Please enter a valid age.";
                $current_menu = "new_user_age";
                $action = "request";
            } else {
                $age = intval($input_text);

                if ($age < 18) {
                    $response = "You must be at least 18 years old to register.";
                    $current_menu = "new_user_age";
                    $action = "request";
                } elseif ($age > 100) {
                    $response = "Please enter your actual age.";
                    $current_menu = "new_user_age";
                    $action = "request";
                } else {
                    //User does not exist in DB
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_user_age',  $input_text);

                    $response = $new_user_district;
                    $current_menu   = "new_user_district";

                    $action = "request";
                }
            }
        } elseif ($last_menu == "new_user_district") {
            $district = $this->menu_helper->getMostSimilarDistrict($input_text, "Uganda");
            $input_text = $district->name ?? null;

            if ($this->menu_helper->checkIfDistrictIsValid($input_text) && strlen($input_text) > 3) {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_user_district',  $input_text);

                $userType = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'farmer_market_user_type');

                if ($userType == "buyer") {
                    $response = "Thank you for registering on the M-Omulimisa Farmer Market. Dial *217*101# to start shopping.";
                } elseif ($userType == "seller") {
                    $response = "Thank you for registering on the M-Omulimisa Farmer Market as a seller. We shall reach out shortly to complete your registration.";
                } elseif ($userType == "service_provider") {
                    $response = "Thank you for registering on the M-Omulimisa Farmer Market as a service provider. We shall reach out shortly to complete your registration.";
                }

                $this->menu_helper->completeFarmersMarketRegistration($sessionId, $phoneNumber);

                $current_menu   = "new_user_confirmation";
                $action         = "end";
            } else {
                $response = "Invalid district name. Please enter a valid district name.";
                $current_menu   = "new_user_district";
                $action         = "request";
            }
        }

        //existing user stuff
        elseif ($last_menu == "farmer_market_categories") {
            //TODO: Get selected products in the selected category and display the products
            if ($input_text == "0") {
                $response  = $main_menu;
                $current_menu   = "main_menu";
                $action = "request";
            } else {
                $catrogry = $this->menu_helper->getSelectedCategoryID($sessionId, $phoneNumber, $input_text);
                $response = $this->menu_helper->getProductsInACategory($sessionId, $phoneNumber, $catrogry);

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_category',  $catrogry);

                $current_menu   = "farmer_market_products";
                $action = "request";
            }
        } elseif ($last_menu == "farmer_market_products") {
            //TODO: Get selected products in the selected category and display the products
            $catrogry = $this->menu_helper->getOptionMappedID($sessionId, $phoneNumber, $input_text);
            $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_product',  $catrogry);

            $product = $this->menu_helper->getSelectedProduct($catrogry);
            $response = $this->menu_helper->getUnitsAndPricingInAProduct($sessionId, $phoneNumber, $product);

            $current_menu   = "farmer_market_units";
            $action = "request";
        } elseif ($last_menu == "farmer_market_units") {
            $productUnitID = $this->menu_helper->getOptionMappedID($sessionId, $phoneNumber, $input_text);

            //TODO: Get selected products in the selected category and display the products
            $catrogry = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'farmer_market_product');
            $unitText = $this->menu_helper->getSelectedProductUnits($productUnitID);
            $product = $this->menu_helper->getSelectedProduct($catrogry);

            $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_units',  $unitText->unit);
            $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_price',  $unitText->price);

            $response = "How many " . $unitText->unit . "s of " . $product->name . " do you want to buy?";

            $current_menu   = "farmer_market_quantity";
            $action = "request";
        } elseif ($last_menu == "farmer_market_quantity") {
            $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_quantity',  $input_text);

            $response = "To which district should we deliver? (eg Kampala)";
            $current_menu   = "farmer_market_district";

            $action = "request";
        } elseif ($last_menu == "farmer_market_district") {
            $district = $this->menu_helper->getMostSimilarDistrict($input_text, "Uganda");
            $input_text = $district->name ?? null;

            if ($this->menu_helper->checkIfDistrictIsValid($input_text) && strlen($input_text) > 3) {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_district',  $input_text);

                $response       = $input_text . "\n";
                $response       .= "Select Subcounty (Enter a number):\n";

                $response       .= $this->menu_helper->getFarmerSubcounties($phoneNumber, $sessionId, $district->id);
                // $response       .= "0) Back\n";

                $current_menu   = "farmer_market_subcounty";
                $action         = "request";
            } else {
                $response = "Invalid district name. Please enter a valid district name.";
                $current_menu   = "farmer_market_district";
                $action         = "request";
            }
        } elseif ($last_menu == "farmer_market_subcounty") {
            $districtName = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'farmer_market_district');
            $district = $this->menu_helper->getMostSimilarDistrict($districtName, "Uganda");

            $subcounty = $this->menu_helper->getSelectedSubcounty($input_text, $district->id);
            $input_text = $subcounty->name ?? null;

            if ($this->menu_helper->checkIfSubcountyIsValid($district->id, $input_text) && strlen($input_text) > 3) {
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_subcounty',  $input_text);
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'selected_subcounty_id',  $subcounty->id);

                $response       = $input_text . "\n";
                $response       .= "Select Parish (Enter a number):\n";
                $response       .= $this->menu_helper->getFarmerParish($phoneNumber, $sessionId, $subcounty->id);

                $current_menu   = "farmer_market_parish";
                $action         = "request";
            } else {
                $response       = "Wrong input!\n";
                $response       .= "Select Subcounty\n";
                $response       .= $this->menu_helper->getSubcountyList($districtId);

                $current_menu   = "farmer_market_subcounty";
                $action         = "request";
            }
        } elseif ($last_menu == "farmer_market_parish") {
            /* $selectedParishID = $this->menu_helper->getOptionMappedID($sessionId, $phoneNumber, $input_text);
             */

            $sessionData = UssdSessionData::whereSessionId($sessionId)
                ->wherePhoneNumber($phoneNumber)
                ->first();

            $subcountyId = $sessionData->selected_subcounty_id;
            $parish = $this->menu_helper->getSelectedParish($input_text, $subcountyId);
            $input_text = $parish->name ?? null;

            if ($this->menu_helper->checkIfParishIsValid($subcountyId, $input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text . "\n";

                $saved_data = \App\Models\Ussd\UssdSessionData::whereSessionId($sessionId)
                    ->wherePhoneNumber($phoneNumber)
                    ->first();

                $quantity = $sessionData->farmer_market_quantity;
                $unitString = $sessionData->farmer_market_units;
                $price = $sessionData->farmer_market_price;

                $catrogry = $saved_data->farmer_market_product;
                $product = $this->menu_helper->getSelectedProduct($catrogry);

                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'farmer_market_parish',  $input_text);
                $quantityInNumbers = intval($quantity); // Convert the input_text string to an integer
                $subtotal = $quantityInNumbers * $price;

                $response = "You are about to buy " . $quantity . " " . $unitString . "s of " . $product->name . " at UGX " . $price . " each. Subtotal: UGX " . number_format($subtotal) . "\nPress 1 to confirm.";

                $current_menu   = "farmer_market_confirmation";
            } else {
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Parish\n";
                $response       .= $this->menu_helper->getParishList($subcountyId);
                $response       .= "0) Back\n";
                $current_menu   = "farmer_market_parish";
            }

            $action = "request";
        } elseif ($last_menu == "farmer_market_confirmation") {
            if ($input_text == "1") {
                $result =    $this->menu_helper->completeFarmersMarketPurchase($sessionId, $phoneNumber);

                if ($result == true) {
                    $response = "Order successfully placed. Thank you for shopping with M-Omulimisa";
                    $current_menu   = "farmer_market_finished";
                    $action = "end";
                } else {
                    $response = "Error placing order. Please try again or call us directly on +256788322929";
                    $current_menu   = "farmer_market_finished";
                    $action = "end";
                }
            } else {
                $response = "Request cancelled. Dial *217*101# to restart shopping.";
                $current_menu   = "farmer_market_finished";
                $action = "end";
            }
        }

        /******************* START ADVISORY SHIT *******************/
        /*  elseif ($last_menu == "advisory_option_menu") {
            if ($input_text == 1) {

                $action         = "request";

                $languages = $this->menu_helper->getMenuLanaguages(4);

                $response  = "Select language!\n";
                foreach ($languages as $language) {
                    $response .= $language->position . ") " . $language->language . "\n";
                }

                $current_menu   = "tip_language_menu";
                $module         = 'advisory';
            } else if ($input_text == 2) {

                $action         = "request";

                $languages = $this->menu_helper->getMenuLanaguages(4);

                $response  = "Select language!\n";
                foreach ($languages as $language) {
                    $response .= $language->position . ") " . $language->language . "\n";
                }

                $current_menu   = "evaluation_language_menu";
            } else {
                $action         = "request";
                $response       = "Invalid input!\n";
                $current_menu   = "advisory_option_menu";
            }
        } elseif ($last_menu == "tip_language_menu") {
            $menu_id = 4;

            $language_check = $this->menu_helper->checkIfUssdLanguageIsValid($input_text);

            if ($language_check) {

                $language = $this->menu_helper->getLanguage($input_text);

                info($language);

                if ($language->language == 'English') {

                    $action         = "request";
                    $response       = "Which topic would you like to receive agronomic tips on?\n";
                } else if ($language->language == 'Lumasaba') {

                    $action         = "request";
                    $response       = "Shisintsa shina shesi wandikanile khufunakho khulekela?\n";
                } else if ($language->language == 'Runyankore') {

                    $action         = "request";
                    $response       = "Neishomoki eriwakubeire noyenda oshomesibweho?\n";
                } else {
                    $action         = "request";
                    $response       = "Which topic would you like to receive agronomic tips on?\n";
                }

                $advisory_topics =   $this->menu_helper->getAdvisoryTopics($input_text, $menu_id, $sessionId);



                foreach ($advisory_topics as $topics) {

                    $response .= $topics->position . ") " . $topics->topic . "\n";
                }

                $current_menu   = "advisory_menu";
            } else {
                $action         = "request";
                $response       = "Invalid input!\n";
                $current_menu   = "language_menu";
            }
        } elseif ($last_menu == "advisory_menu") {

            $advisory_questions =   $this->menu_helper->getAdvisoryQuestions($input_text, $sessionId);

            $action         = "request";
            $response       = $advisory_questions->question . "\n";
            foreach ($advisory_questions->options as $option) {

                $response .= $option->position . ") " . $option->option . "\n";
            }

            $current_menu   = "advisory_subtopic_menu";
        } elseif ($last_menu == "advisory_subtopic_menu") {
            dispatch(new SendUssdAdvisoryMessage($sessionId, $input_text));

            $ussd_lang =  $this->menu_helper->getSessionLanguage($sessionId);

            if ($ussd_lang->language == 'English') {

                $action         = "end";
                $response       = "Thank you. Advisory will be sent to you shortly";
            } else if ($ussd_lang->language == 'Lumasaba') {

                $action         = "end";
                $response       = "Bubakha buno utsya khubufuna mumbuka ikhali iye aleyi ta.\n";
            } else if ($ussd_lang->language == 'Runyankore') {

                $action         = "end";
                $response       = "Webare. Okushomesebwa nozakutandika kukutunga omukaire kakye\n";
            } else {
                $action         = "end";
                $response       = "Thank you. Advisory will be sent to you shortly";
            }

            $current_menu  = "Sending advisory";
        } elseif ($last_menu == "evaluation_language_menu") {
            $menu_id = 4;

            $language = UssdLanguage::select('id')->where('menu_id', $menu_id)->where('position', $input_text)->first();

            if ($language === null) {

                $action         = "request";
                $response       = "Invalid input!\n";
                $current_menu   = "evaluation_language_menu";
            } else {
                $data = [

                    'language_id' => $language->id
                ];

                UssdSession::whereSessionId($sessionId)->update(['data' => $data]);

                $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(1, $sessionId);

                $action         = "request";
                $response       = $evaluation_questions->evaluation_question . "\n";
                foreach ($evaluation_questions->options as $option) {

                    $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
                }

                $current_menu   = "advisory_evaluation_two";
            }
        } elseif ($last_menu == "advisory_evaluation_two") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 1, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(2, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_three";
        } elseif ($last_menu == "advisory_evaluation_three") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 2, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(3, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_four";
        } elseif ($last_menu == "advisory_evaluation_four") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 3, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(4, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_five";
        } elseif ($last_menu == "advisory_evaluation_five") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 4, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(5, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_six";
        } elseif ($last_menu == "advisory_evaluation_five") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 4, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(5, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_six";
        } elseif ($last_menu == "advisory_evaluation_six") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 5, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(6, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_seven";
        } elseif ($last_menu == "advisory_evaluation_seven") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 6, $input_text);

            $evaluation_questions =   $this->menu_helper->getEvaluationQuestions(7, $sessionId);

            $action         = "request";
            $response       = $evaluation_questions->evaluation_question . "\n";
            foreach ($evaluation_questions->options as $option) {

                $response .= $option->position . ") " . $option->evaluation_question_option . "\n";
            }

            $current_menu   = "advisory_evaluation_end";
        } elseif ($last_menu == "advisory_evaluation_end") {
            $save_answer = $this->menu_helper->saveEvaluationAnswer($sessionId, 7, $input_text);
            $action         = "end";
            $response       = "Thank you.";
            $current_menu  = "advisory_evaluation_end";
        } else {
            $response  = "An Error occured. Contact M-Omulimisa team for help!";
            $current_menu = "system_error";
            $action         = "end";
        } */

        //----------------HANDLE THE ACTUAL SENDING OF THE USSD CODE REQUEST--------------------------

        //save the last menu
        $this->menu_helper->saveLastMenu($sessionId, $phoneNumber, $current_menu);

        //save the field in the step
        if (!is_null($field) && $input_text != "0" && !is_null($input_text)) {
            $this->menu_helper->saveToField($sessionId, $phoneNumber, $field, $input_text);
        }

        header('Content-Type: text/html');  // plain
        //format URL-encoded response
        $response = urldecode("responseString=" . $response) . "&action=" . urldecode($action);
        //logIt("Response sent: ".$response);
        print($response);
    }
}
