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

    // public function __construct(MenuFunctions $menu_helper, YoPay $yopay, MtnPay $mtnpay)
    // {
    //     $this->menu_helper  = $menu_helper;
    //     $this->yopay        = $yopay;
    //     $this->mtnpay       = $mtnpay;
    //     $this->_reference   = config('yopay.reference');
    // }

    /**
     * Receiving parameters from Africa Is Talking API
     * @return  - String request from user or - String closing ussd session
     */
    public function index(Request $request)
    {
        // Log::info(['YoUssdData' => $request->all()]);
        //sent variables

        $sessionId      = $request->transactionId;
        $transactionTime    = $request->transactionTime;
        $phoneNumber             = $request->msisdn;
        $serviceCode    = $request->ussdServiceCode;
        $ussdRequestString  = $request->ussdRequestString;
        $response           = $request->response;

        $input_text   = $ussdRequestString; //end($text_chain); //last user input
        $field        = null; //column in subscription table
        $display_main_menu = false;

        //get the last menu for this session
        $last_menu = null; // $this->menu_helper->getLastMenu($sessionId, $phoneNumber);

        $main_menu = "Welcome to M-Omulimisa\n";
        $main_menu .= "1) Agriculture Insurance \n";
        $main_menu .= "2) Market Information \n";
        $main_menu .= "3) Weather Information";

        if ($last_menu == null) {
            $response  = $main_menu;
            $action = "request";
            $current_menu = "main_menu";

            //create record
            // $this->menu_helper->startSubscription($sessionId, $phoneNumber, $user_type);

        }
        elseif ($last_menu == "main_menu") {
            // $input_text == '1'
        }
        else {
            $response  = "An Error occured. Contact M-Omulimisa team for help!";
            $current_menu = "system_error";
            $action         = "end";
        } 

        //save the last menu
        // $this->menu_helper->saveLastMenu($sessionId, $phoneNumber, $current_menu);
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
