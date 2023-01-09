<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use Log;
use Response;
use Validator;
use Carbon\Carbon;
// use App\Api\v1\YoPay;
// use App\Api\v1\MtnPay;
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

        $languages_menu  = "Select your preferred language!\n";
        $languages_menu .= "1) Acholi\n";
        $languages_menu .= "2) English\n";
        $languages_menu .= "3) Lango\n";
        $languages_menu .= "4) Luganda\n";
        $languages_menu .= "5) Lugbara\n";
        $languages_menu .= "6) Runyakitara\n";
        $languages_menu .= "7) Other";

        if ($last_menu == null) {
            $response  = $main_menu;
            $action = "request";
            $current_menu = "main_menu";

            //create record
            // $this->menu_helper->startSubscription($sessionId, $phoneNumber, $user_type);

        }
        elseif ($last_menu == "main_menu") {            
            $action = "request";

            if($input_text == '1'){
                $response       = "Farmer's phone number\n";
                $response       .= "1) This number\n";
                $response       .= "2) Another number";
                $current_menu   = "insurance_phone_option";
            }
            elseif ($input_text == '2') {
                // Ask language for Market information
                $response     = $languages_menu;
                $current_menu = "market_languages_menu";
            }
            elseif ($input_text == '3') {
                // Ask language Weather information
                $response     = $languages_menu;
                $current_menu = "weather_languages_menu";
            }
            else {
                $action         = "end";
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input"; 
            }
        }

        /******************* START INSURANCE *******************/

        elseif ($last_menu == "insurance_phone_option" && $input_text == '1' || $last_menu == "insurance_phone") {
            $action         = "request";
            $response       = "Enter farmer's name e.g Ninsiima Daniel";
            $current_menu   = "insurance_name";
        } 
        elseif ($last_menu == "insurance_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter farmer's phone e.g 07XXXXXXXX";
            $current_menu   = "insurance_phone";
        }
        elseif ($last_menu == "insurance_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input"; 
        } 
        elseif ($last_menu == "insurance_name") {
            // check if name is valid
            // check if phone no is valid
            $action         = "request";
            $response       = "Enter District e.g Kampala";
            $current_menu   = "insurance_district";
        } 
        elseif ($last_menu == "insurance_district") {
            // check if district is valid
            // fetch active/available seasons
            $action         = "request";
            $response       = "Select season\n";
            // $response       .= $this->menu_helper->seasonList();
            $response       .= "1) Season A (Mar23-May23)\n2) Season B (Set23-Nov23)";
            $current_menu   = "insurance_season";
        } 
        elseif ($last_menu == "insurance_season") {
            // check if season is valid
            // fetch crop list
            $action         = "request";
            $response       = "Select item to insure\n";
            $response       = "1) Beans\n2)Maize\n3)SoyaBean\n4)Sorghum";
            $current_menu   = "insurance_item";
        }  
        elseif ($last_menu == "insurance_item") {
            // check if crop is valid
            $action         = "request";
            $response       = "Enter no. of acres\n";
            $current_menu   = "insurance_acreage";
        }   
        elseif ($last_menu == "insurance_acreage") {
            // check if acreage value is valid
            $action    = "request";
            $response  = "Insuring [acreage] acres of [item] for [phone] at ugx [sum_insured] in [season]. Pay premium of ugx [amount]\n";
            $response .= "1) Yes\n";
            $response .= "2) No";
            $current_menu   = "insurance_confirmation";
        }  
        elseif ($last_menu == "insurance_confirmation") {
            // check if crop is valid

            $action         = "end";
            
            if ($input_text == '1') {
                $response       = "Thank you for subscribing.\n";
                $response       .= "Check [phone] to approve the payment\n";
                $current_menu   = "insurance_confirmed";
            }
            elseif($input_text == '2'){
                $response       = "Transaction has been cancelled";
                $current_menu   = "insurance_cancelled";
                // $input_text     = "CANCELLED";              
            }
            else{
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input";                 
            }
        } 
        
        /******************* START MARKET *******************/

        elseif ($last_menu == "market_languages_menu") {
            $action         = "request";
            $response       = "Subscribing phone number\n";
            $response       .= "1) This number\n";
            $response       .= "2) Another number";
            $current_menu   = "market_phone_option";
        }
        elseif ($last_menu == "market_phone_option" && $input_text == '1' || $last_menu == "market_phone") {
            $action         = "request";
            $response       = "Enter farmer's name e.g Ninsiima Daniel";
            $current_menu   = "market_name";
        } 
        elseif ($last_menu == "market_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter farmer's phone e.g 07XXXXXXXX";
            $current_menu   = "market_phone";
        }
        elseif ($last_menu == "market_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input"; 
        } 
        elseif ($last_menu == "market_name") {
            // check if name is valid
            // check if phone no is valid
            $action         = "request";
            $response       = "Enter District e.g Kampala";
            $current_menu   = "market_district";
        } 
        elseif ($last_menu == "market_district") {
            // check if district is valid
            $action         = "request";
            $response       = "Select package\n";
            $response       .= "1) Cereals\n2) Legumes\n3) Tubers\n4) Cereal, Legumes\n5) Cereal, Tubers\n6) Legumes, Tubers\n7) All";
            $current_menu   = "market_package";
        } 
        elseif ($last_menu == "market_package") {
            // check if package is valid
            $action         = "request";
            $response       = "Select frequency\n";
            $response       .= "1) Trial\n2) Weekly\n3) Monthly\n4) Yearly";
            $current_menu   = "market_frequency";
        }  
        elseif ($last_menu == "market_frequency" && $input_text != '1') {
            // check if frequency is valid
            // check if input is valid
            $action         = "request";
            $response       = "Enter number of [frequency]";
            $current_menu   = "market_period";
        }  
        elseif ($last_menu == "market_period" || $last_menu == "market_frequency" && $input_text == '1') {
            // check if acreage value is valid
            $action    = "request";
            $response  = "Subscribing for [package] market info for [period] at ugx [amount]\n";
            $response .= "1) Confirm\n";
            $response .= "2) Cancel";
            $current_menu   = "market_confirmation";
        }  
        elseif ($last_menu == "market_confirmation") {
            // check if crop is valid

            $action         = "end";
            
            if ($input_text == '1') {
                $response       = "Thank you for subscribing.\n";
                $response       .= "Check [phone] to approve the payment\n";
                $current_menu   = "market_confirmed";
            }
            elseif($input_text == '2'){
                $response       = "Transaction has been cancelled";
                $current_menu   = "market_cancelled";
                // $input_text     = "CANCELLED";              
            }
            else{
                $response       = "Invalid input!\n";
                $current_menu   = "invalid_input";                 
            }
        }  
        
        /******************* START WEATHER *******************/

        elseif ($last_menu == "weather_languages_menu") {
            $action         = "request";
            $response       = "Subscribing phone number\n";
            $response       .= "1) This number\n";
            $response       .= "2) Another number";
            $current_menu   = "weather_phone_option";
        }
        elseif ($last_menu == "weather_phone_option" && $input_text == '1' || $last_menu == "weather_phone") {
            $action         = "request";
            $response       = "Enter farmer's name e.g Ninsiima Daniel";
            $current_menu   = "weather_name";
        } 
        elseif ($last_menu == "weather_phone_option" && $input_text == '2') {
            $action         = "request";
            $response       = "Enter farmer's phone e.g 07XXXXXXXX";
            $current_menu   = "weather_phone";
        }
        elseif ($last_menu == "weather_phone_option") {
            $action         = "end";
            $response       = "Invalid input!\n";
            $current_menu   = "invalid_input"; 
        } 
        elseif ($last_menu == "weather_name") {
            // check if name is valid
            // check if phone no is valid
            $action         = "request";
            $response       = "Enter District e.g Kampala";
            $current_menu   = "weather_district";
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
        if (!is_null($field)) {
            // $this->menu_helper->saveToField($sessionId, $phoneNumber, $field, $input_text);
        }

        header('Content-Type: text/html');  // plain
        //format URL-encoded response
        $response = urldecode("responseString=".$response)."&action=".urldecode($action);
        //logIt("Response sent: ".$response);
        print($response);
    } 

}
