<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use Log;
use Response;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\Ussd\MenuFunctions;

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
        $main_menu .= "3) Weather Information";

        $languages_menu  = "Select language!\n";
        $languages_menu .= "1) English\n";
        $languages_menu .= "2) Acholi & Lango\n";
        $languages_menu .= "3) Lango\n";
        $languages_menu .= "4) Luganda\n";
        $languages_menu .= "5) Runyakitara";

        $subscriber     = "Subscribe for\n";
        $subscriber     .= "1) Self\n";
        $subscriber     .= "2) Another";

        $enter_phone = "Enter phone e.g 07XXXXXXXX";
        $invalid_phone = "Invalid phone number";

        // "Enter no. of acres\n";
        $acreage = "Acreage you want to insure\n";
        $acreage .= "1) Half acre\n";
        $acreage .= "2) 1 acre\n";
        $acreage .= "3) 2 acre\n";
        $acreage .= "4) 3 acre\n";
        $acreage .= "5) 4 acre\n";
        $acreage .= "6) 5 acre\n";

        $insure_more = "Want to insure another crop?\n";
        $insure_more .= "1) No\n";
        $insure_more .= "2) Yes";

        if ($last_menu == null) {
            $response  = $main_menu;
            $action = "request";
            $current_menu = "main_menu";
        }
        elseif ($last_menu == "main_menu") {            
            $action = "request";

            if($input_text == '1'){
                $response       = $subscriber;
                $current_menu   = "insurance_phone_option";
                $module         = 'insurance';
            }
            elseif ($input_text == '2') {
                $response       = $subscriber;
                $current_menu   = "market_phone_option";
                $module         = 'market';
            }
            elseif ($input_text == '3') {
                // Ask language Weather information
                $response       = $subscriber;
                $current_menu   = "weather_phone_option";
                $module         = 'weather';
            }
            else {
                $action         = "end";
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input"; 
            }
            //create record
            if(isset($module)) $this->menu_helper->startMenu($sessionId, $phoneNumber, $module);
        }

        /******************* START INSURANCE *******************/

        elseif ($last_menu == "insurance_phone_option" && $input_text == '1' || $last_menu == "insurance_phone" || $last_menu == 'insurance_subcounty' && $input_text == '0') {
            $action         = "request";

            if ($last_menu == "insurance_phone" && ! $this->menu_helper->isLocalPhoneValid($input_text, '256')) {
                $response       = $invalid_phone."\n";
                $response       .= $enter_phone;
                $current_menu   = "insurance_phone";
            }
            else{
                $response       = "Enter District e.g Kampala";
                $current_menu   = "insurance_district";

                if ($last_menu != "insurance_phone") {
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_subscrption_for', 'self');
                    $input_text = $phoneNumber;
                }
                else{
                    $input_text = $this->menu_helper->formatPhoneNumbers($phoneNumber, '256', 'international');
                }

                if($input_text != '0') $field = 'insurance_subscriber';
            }
        } 
        elseif ($last_menu == "insurance_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter phone e.g 07XXXXXXXX";
            $current_menu   = "insurance_phone";
            $field          = "insurance_subscrption_for";
            $input_text     = "another";
        }
        elseif ($last_menu == "insurance_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input"; 
        }
        elseif ($last_menu == "insurance_district") {

            $district = $this->menu_helper->getMostSimilarDistrict($input_text, "Uganda");
            $input_text = $district->name ?? null;

            if ($this->menu_helper->checkIfDistrictIsValid($input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text."\n";
                $response       .= "Select Subcounty:\n";
                
                $districtId = $this->menu_helper->getDistrict($district->id, 'id');
                
                $response       .= $this->menu_helper->getSubcountyList($districtId);
                $response       .= "0) Back\n";
                $current_menu   = "insurance_subcounty";

                $field = "insurance_district";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_district_id', $districtId);
            }
            else{
                $action         = "request";
                $response       = "Wrong District!\n";
                $response       .= "Enter District e.g Kampala";
                $current_menu   = "insurance_district";
            }
        }
        elseif ($last_menu == "insurance_subcounty") {

            $districtId = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_district_id');
            $subcounty = $this->menu_helper->getSelectedSubcounty($input_text, $districtId);
            $input_text = $subcounty->name ?? null;

            if ($this->menu_helper->checkIfSubcountyIsValid($districtId, $input_text) && strlen($input_text) > 3) {
                $action         = "request";
                $response       = $input_text."\n";
                $response       .= "Select season:\n";
                $response       .= $this->menu_helper->insuranceSeasonList();
                // $response       .= "0) Back\n";
                $current_menu   = "insurance_season";

                $field = "insurance_subcounty";
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_subcounty_id', $subcounty->id);
            }
            else{
                $action         = "request";
                $response       = "Wrong input!\n";
                $response       .= "Select Subcounty\n";
                $response       .= $this->menu_helper->getSubcountyList($districtId);
                $response       .= "0) Back\n";
                $current_menu   = "insurance_subcounty";
            }
        } 
        elseif ($last_menu == "insurance_season" || $last_menu == "insurance_another" && $input_text == "2") {
            $action = "request";

            if($last_menu == "insurance_another") {
                $seasonId   = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_season_id');
                $input_text = $this->menu_helper->getSeasonMenu($seasonId);
            }

            if ($this->menu_helper->checkIfSeasonIsValid($input_text)) {
                $seasonId       = $this->menu_helper->getSeasonDetail($input_text, 'id');
                $response       = "Crop you want to insure:\n";
                $response       .= $this->menu_helper->seasonItemList($seasonId);
                $current_menu   = "insurance_item";

                $field = "insurance_season_id";
                $input_text = $seasonId;
            }
            else{
                $response       = "Wrong input! Select season:\n";
                $response       .= $this->menu_helper->insuranceSeasonList();
                $current_menu   = "insurance_season";
            }
        }  
        elseif ($last_menu == "insurance_item") {
            $action         = "request";
            $seasonId       = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_season_id');
            if ($this->menu_helper->checkIfSeasonItemIsValid($seasonId, $input_text)) {
                $response       = $acreage;
                $current_menu   = "insurance_acreage";
                $field          = "insurance_enterprise_id";
                $input_text     = $this->menu_helper->getSeasonItemDetails($seasonId, $input_text, 'enterprise_id');
            }
            else{
                $response       = "Wrong Item!\n";
                $response       .= "Select item to insure:\n";
                $response       .= $this->menu_helper->seasonItemList($seasonId);
                $current_menu   = "insurance_item";
            }
        }   
        elseif ($last_menu == "insurance_acreage") {
            $action    = "request";
            if (is_numeric($input_text) && $input_text > 0) {

                $input_text = $this->menu_helper->getAcerage($input_text);

                $seasonId       = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_season_id');
                $enterprise_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_enterprise_id');
                $sum_insured    = $this->menu_helper->getPremiumOptionDetails($seasonId, $enterprise_id, 'sum_insured_per_acre');
                $premium        = $this->menu_helper->getPremiumOptionDetails($seasonId, $enterprise_id, 'premium_per_acre');

                $response       = $insure_more;
                $current_menu   = "insurance_another";
                $field          = "insurance_acreage";
                
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_sum_insured', ($sum_insured * $input_text));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_premium', ($premium * $input_text));
            }
            else{
                $response       = "Wrong input!\n";
                $response       .= $acreage;
                $current_menu   = "insurance_acreage";
            }
        }
        elseif ($last_menu == "insurance_another") {
            $action    = "request";
            if ($input_text == "1") {
                $input_text     = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_acreage');

                $seasonId       = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_season_id');
                $seasonName     = $this->menu_helper->getSeason($seasonId, 'name');

                $enterprise_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_enterprise_id');
                $enterpriseName = $this->menu_helper->getEnterprise($enterprise_id, 'name');

                $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_subscriber');
                $sum_insured    = $this->menu_helper->getPremiumOptionDetails($seasonId, $enterprise_id, 'sum_insured_per_acre');
                $premium        = $this->menu_helper->getPremiumOptionDetails($seasonId, $enterprise_id, 'premium_per_acre');

                $response  = "Insuring ".$input_text."A of ".$enterpriseName." for ".$phone." at ugx".number_format($sum_insured * $input_text)." in ".$seasonName.". Pay premium of ugx".number_format(($premium * $input_text));                
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                $response .= "\n1) Yes\n";
                $response .= "2) No";
                $current_menu   = "insurance_confirmation";
                $field          = "insurance_acreage";
                
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_sum_insured', ($sum_insured * $input_text));
                $this->menu_helper->saveToField($sessionId, $phoneNumber, 'insurance_premium', ($premium * $input_text));
            }
            else{
                $response       = "Wrong input!\n";
                $response       .= $insure_more;
                $current_menu   = "insurance_another";
            }
        }  
        elseif ($last_menu == "insurance_confirmation") {
            $action         = "end";
            
            if ($input_text == '1') {
                if ($this->menu_helper->completeInsuranceSubscription($sessionId, $phoneNumber)) {
                    $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'insurance_subscriber');
                    $response       = "Thank you for subscribing.\n";
                    $response       .= "Check ".$phone." to approve the payment\n";
                }
                else{
                    $response = "Subscription was unsuccessful. Please try again";
                }

                $current_menu   = "insurance_confirmed";
                $field          = "insurance_confirmation";
            }
            elseif($input_text == '2') {
                $response       = "Transaction has been cancelled";
                $current_menu   = "insurance_cancelled";
                // $input_text     = "CANCELLED";              
            }
            else{
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

            if ($last_menu == "market_phone" && ! $this->menu_helper->isLocalPhoneValid($input_text, '256')) {
                $response       = $invalid_phone."\n";
                $response       .= $enter_phone;
                $current_menu   = "market_phone";
            }
            else{
                $response       = "Select package:\n";
                $response       .= $this->menu_helper->getPackageList();
                $current_menu   = "market_package";

                if ($last_menu != "market_phone") {
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_subscrption_for', 'self');
                    $input_text = $phoneNumber;
                }
                else{
                    $input_text = $this->menu_helper->formatPhoneNumbers($phoneNumber, '256', 'international');
                }

                $field          = 'market_subscriber';
            }
        } 
        elseif ($last_menu == "market_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = $enter_phone;
            $current_menu   = "market_phone";

            $field          = 'market_subscrption_for';
            $input_text     = 'another';
        }
        elseif ($last_menu == "market_phone_option") {
            $action         = "request";
            $response       = "Invalid input!\n";
            $response       .= $subscriber;
            $current_menu   = "market_phone_option"; 
        } 
        elseif ($last_menu == "market_package") {
            $action         = "request";
            if (! $this->menu_helper->isPackageMenuValid($input_text)) {
                $response       = "Invalid input!\n";
                $response       .= $this->menu_helper->getPackageList();
                $current_menu   = "market_package";
            }
            else{
                $package_id   = $this->menu_helper->getPackageId($input_text);
                $response     = "Select language:\n";
                $response     .= $this->menu_helper->getPackageLanguages($package_id);
                $current_menu = "market_languages_menu"; 

                $field          = 'market_package_id';
                $input_text     = $package_id;
            }
        }        
        elseif ($last_menu == "market_languages_menu") {
            $action         = "request";
            $package_id   = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');

            // check if package is valid
            if (! $this->menu_helper->isPackageLanguageValid($package_id, $input_text)) {
                $response       = "Invalid input! Select language:\n";
                $response       .= $this->menu_helper->getPackageLanguages($package_id);
                $current_menu = "market_languages_menu"; 
            }
            else{
                $response       = "Select frequency:\n";
                $response       .= $this->menu_helper->getPackageFrequencies($package_id);
                $current_menu   = "market_frequency"; 

                $field          = 'market_language_id';
                $input_text     = $this->menu_helper->getPackageLanguageId($package_id, $input_text);               
            }
        }  
        elseif ($last_menu == "market_frequency") {
            $action         = "request";
            $package_id   = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');

            // check if frequency is valid -- check if input is valid
            if (! $this->menu_helper->isPackageFrequencyValid($package_id, $input_text)) {
                $response       = "Invalid input! Select frequency:\n";
                $response       .= $this->menu_helper->getPackageFrequencies($package_id);
                $current_menu   = "market_frequency"; 
            }
            else{
                $input_text     = $this->menu_helper->getPackageFrequency($package_id, $input_text);

                $response       = "Enter number of ".str_replace('ly', 's', $input_text);
                $current_menu   = "market_period";                
                
                $field = 'market_frequency';
            }
        }  
        elseif ($last_menu == "market_period" || $last_menu == "market_confirmation" && $input_text != "1" && $input_text != "2") {
            $action    = "request";
            $frequency = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_frequency');
            $_frequency = str_replace('ly', 's', $frequency);

            // Back to this step -- Retrieve previous input
            if($last_menu == "market_confirmation") $input_text = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_frequency_count');

            // check if acreage value is valid
            if (!is_numeric($input_text) && $input_text >= 0) {
                $response       = "Invalid input!\n";
                $response       .= "Enter number of ".$_frequency;
                $current_menu   = "market_period";  
            }
            else{
                $package_id  = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_package_id');
                $enterprises = $this->menu_helper->getPackageEnterprises($package_id);
                $cost        = $this->menu_helper->getPackageCost($package_id, $frequency, $input_text);
                $currency    = 'UGX';   

                if (!is_null($cost)) {
                    // code...
                    $response  = "Subscribing for ".$enterprises." market info for ".$input_text.$_frequency." at ".$currency.''.number_format($cost * $input_text);              
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'confirmation_message', $response);

                    $response .= "\n1) Confirm\n";
                    $response .= "2) Cancel";
                    $current_menu   = "market_confirmation"; 
                    
                    $this->menu_helper->saveToField($sessionId, $phoneNumber, 'market_cost', ($cost * $input_text));  

                    $field = 'market_frequency_count';             
                }
                else{
                    $action         = "end";
                    $response       = "Selected package has no pricing";
                    $current_menu   = "market_cost_error"; 
                }
            }
        }  
        elseif ($last_menu == "market_confirmation") {
            // check if crop is valid

            $action         = "end";
            
            if ($input_text == '1') {

                // create the subscription and payment 
                if ($this->menu_helper->completeMarketSubscription($sessionId, $phoneNumber)) {
                    $phone          = $this->menu_helper->sessionData($sessionId, $phoneNumber, 'market_subscriber');
                    $response       = "Thank you for subscribing.\n";
                    $response       .= "Check ".$phone." to approve the payment\n";
                }
                else{
                    $response = "Subscription was unsuccessful. Please try again";
                }

                $current_menu   = "market_confirmed";
                $field = 'market_confirmation';
            }
            elseif($input_text == '2'){
                $response       = "Transaction has been cancelled";
                $current_menu   = "market_cancelled";
                // $input_text     = "CANCELLED";              
            }
            else{
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
            // check if name is valid
            // check if phone no is valid
            $action         = "request";
            $response       = "Enter District e.g Kampala";
            $current_menu   = "weather_district";
        }
        elseif ($last_menu == "weather_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter phone e.g 07XXXXXXXX";
            $current_menu   = "weather_phone";
        }
        elseif ($last_menu == "weather_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input"; 
        } 
        elseif ($last_menu == "weather_district") {
            // check if district is valid
            // fetch attached subcounties
            $action         = "request";
            $response       = "Enter Subcounty e.g Kawempe";
            $current_menu   = "weather_subcounty";
        } 
        elseif ($last_menu == "weather_subcounty") {
            // check if subcounty is valid
            // fetch attached parishes
            $action         = "request";
            $response       = "Enter Parish e.g Kazo";
            $current_menu   = "weather_parish";
        } 
        elseif ($last_menu == "weather_parish") {
            // check if parish is valid
            $action         = "request";
            $response       = $languages_menu;
            $current_menu   = "weather_languages_menu";
        }  
        elseif ($last_menu == "weather_languages_menu") {
            // check if parish is valid
            $action         = "request";
            $response       = "Select frequency\n";
            $response       .= "1) Trial\n2) Weekly\n3) Monthly\n4) Yearly";
            $current_menu   = "weather_frequency";
        }  
        elseif ($last_menu == "weather_frequency" && $input_text != '1') {
            // check if name is valid
            // check if phone no is valid
            $action         = "request";
            $response       = "Enter number of [frequency]";
            $current_menu   = "weather_period";
        }  
        elseif ($last_menu == "weather_period" || $last_menu == "weather_frequency" && $input_text == '1') {
            // check if acreage value is valid
            $action    = "request";
            $response  = "Subscribing for weather info in [Parish, Subcounty, District] for [period] at ugx [amount].\n";
            $response .= "1) Confirm\n";
            $response .= "2) Cancel";
            $current_menu   = "weather_confirmation";
        }  
        elseif ($last_menu == "weather_confirmation") {
            // check if crop is valid

            $action         = "end";
            
            if ($input_text == '1') {
                $response       = "Thank you for subscribing.\n";
                $response       .= "Check [phone] to approve the payment\n";
                $current_menu   = "weather_confirmed";
            }
            elseif($input_text == '2'){
                $response       = "Transaction has been cancelled";
                $current_menu   = "weather_cancelled";
                // $input_text     = "CANCELLED";              
            }
            else{
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input";                 
            }
        } 

        else {
            $response  = "An Error occured. Contact M-Omulimisa team for help!";
            $current_menu = "system_error";
            $action         = "end";
        } 

        //save the last menu
        $this->menu_helper->saveLastMenu($sessionId, $phoneNumber, $current_menu);

        //save the field in the step
        if (!is_null($field) && $input_text != "0" && !is_null($input_text)) {
            $this->menu_helper->saveToField($sessionId, $phoneNumber, $field, $input_text);
        }

        header('Content-Type: text/html');  // plain
        //format URL-encoded response
        $response = urldecode("responseString=".$response)."&action=".urldecode($action);
        //logIt("Response sent: ".$response);
        print($response);
    } 

}
