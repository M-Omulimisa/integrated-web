<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use Log;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Settings\Country;
use App\Models\Ussd\UssdSession;
use App\Models\Market\MarketPackage;
use App\Models\Ussd\UssdSessionData;
use App\Models\Settings\CountryProvider;
use App\Models\Market\MarketSubscription;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackagePricing;
use App\Models\Payments\SubscriptionPayment;

use App\Services\Payments\PaymentServiceFactory;

class MenuFunctions
{

    public function getLastMenu($sessionId, $phoneNumber)
    {
        $session = UssdSession::whereSessionId($sessionId)
                                ->wherePhoneNumber($phoneNumber)
                                ->first();
        return $session ? $session->last_menu : null;
    }

    public function checkSession($sessionId, $phoneNumber)
    {
        //check for the sessions existence
        $sessions = UssdSession::whereSessionId($sessionId)
                                ->wherePhoneNumber($phoneNumber)
                                ->count();
        return $sessions > 0 ? true : false;
    }

    public function saveLastMenu($sessionId, $phoneNumber, $current_menu)
    {
        $last_session = new UssdSession;

        if ($this->checkSession($sessionId, $phoneNumber)) {
            UssdSession::whereSessionId($sessionId)
                        ->wherePhoneNumber($phoneNumber)
                        ->update(['last_menu' => $current_menu]);
        } 
        else {
            //create new session if does not exist
            UssdSession::create([
                'session_id'    => $sessionId,
                'phone_number'  => $phoneNumber,
                'last_menu'     => $current_menu
            ]);
        }
    }

    /**
     * 
     * Create the ussd data capture if it doesnt exist
     * 
     * @return  param value
     */
    public function startMenu($sessionId, $phoneNumber, $module)
    {
        if (!$this->checkSessionData($sessionId, $phoneNumber)) {
            UssdSessionData::create([
                'session_id'    => $sessionId,
                'phone_number'  => $phoneNumber,
                'module'        => $module
            ]);
        } 
    }

    public function checkSessionData($sessionId, $phoneNumber)
    {
        //check for the session data existence
        $sessions = UssdSessionData::whereSessionId($sessionId)
                                    ->wherePhoneNumber($phoneNumber)
                                    ->get();
        return count($sessions) > 0 ? true : false;
    }

    /**
     * 
     * Get value of a given parameter
     * 
     * @return  param value
     */
    public function sessionData($sessionId, $phoneNumber, $param)
    {
            $saved_data = UssdSessionData::whereSessionId($sessionId)
                                            ->wherePhoneNumber($phoneNumber)
                                            ->first();
            return $saved_data->$param ?? null;
    }

    /**
     * 
     * Save user's input to given column
     * 
     * @return  param value
     */
    public function saveToField($sessionId, $phoneNumber, $field, $input)
    {
        UssdSessionData::whereSessionId($sessionId)
                        ->wherePhoneNumber($phoneNumber)
                        ->update([$field => $input]);
    }

    /**
     * 
     * Get country details
     * Check if the length is okay else return false
     * Get the network providers
     * Check if the phone format is among the providers else return false
     * 
     * @return  boolean
     */
    public function isLocalPhoneValid($phoneNumber, $dialing_code)
    {
        $country = Country::whereDialingCode($dialing_code)->first();

        if ($country) {
            $local_code = "0";
            
            if(strlen($phoneNumber) == ($country->length - strlen($country->dialing_code)) + strlen($local_code) && 
                substr($phoneNumber, 0, strlen($local_code)) == $local_code)
            {
                $providers = CountryProvider::whereCountryId($country->id)->get();
                if (count($providers)) {
                    foreach ($providers as $provider) {
                        $local_codes = str_replace($country->dialing_code, $local_code, $provider->codes);
                        $local_codes = str_replace(',', '|', $local_codes);
                        if(preg_match("#^(".$local_codes.")(.*)$#i", $phoneNumber) > 0) return true;
                    }
                }
            }
        }

        return false;
    }

    public function formatPhoneNumbers($phoneNumber, $country_code, $format_to='local')
    {
        $country = Country::whereDialingCode($country_code)->first();

        $local_code = 0;

        if ($country) {
            if ($format_to === "local") {
                if (strlen($phoneNumber) == $country->length) {
                    $formatted_number = preg_replace('/^'.$country_code.'/', $local_code, $phoneNumber);
                } else {
                    $formatted_number = $phoneNumber;
                }
            } elseif ($format_to === 'international') {
                if (strlen($phoneNumber) == (($country->length - strlen($country->dialing_code)) + strlen($local_code)) && substr($phoneNumber, 0, strlen($local_code)) == $local_code) {
                    $formatted_number = $country_code . substr($phoneNumber, strlen($local_code), ($country->length - strlen($country->dialing_code)));
                } else {
                    $formatted_number = $phoneNumber;
                }
                
            }
            return $formatted_number;
        }        
    }

    public function getServiceProvider($phoneNumber, $param)
    {
        $dialing_code = substr($phoneNumber, 0, 5);
        $provider = CountryProvider::where('codes', 'LIKE', '%'.$dialing_code.'%')->first();
        return $provider ? $provider->$param : null;
    }

    /**********************************MARKET***********************************************/

    /**
     * Get packages
     * Get enterprises of each package
     * Format them as a menu list
     * 
     * @return string menu list  
     */
    public function getPackageList()
    {
        $list = '';
        $packages = MarketPackage::whereStatus(true)->orderBy('menu', 'ASC')->get();
        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $items = '';
                if (count($package->enterprises)) {
                    foreach ($package->enterprises as $enterprise) {
                        $items .= $enterprise->enterprise->name.','; 
                    }
                }
                $list .= $package->menu.") ".rtrim($items, ',')."\n";
            }
            return $list;
        }
        return 'No packages available!';
    }

    /**
     * Get package id using menu number
     * 
     * @return  int $id
     */
    public function getPackageId($menu)
    {
        $package = MarketPackage::whereMenu($menu)->whereStatus(true)->first();
        return $package->id ?? null;
    } 

    /**
     * Check if an active menu has many packages 
     * Check if package is valid using menu
     * 
     * @return  int $id
     */
    public function isPackageMenuValid($menu)
    {
        $packages = MarketPackage::whereMenu($menu)->whereStatus(true)->count();
        if($packages > 1) return false;

        $package = MarketPackage::whereMenu($menu)->whereStatus(true)->first();
        return $package ? true : false;
    } 

    /**
     * Get languages of a package 
     * Format them as a menu list
     * 
     * @return string menu list  
     */
    public function getPackageLanguages($packageId)
    {
        $list = '';
        $languages = MarketPackageMessage::wherePackageId($packageId)->orderBy('menu', 'ASC')->get();
        if (count($languages) > 0) {
            foreach ($languages as $language) {
                $list .= $language->menu.") ".$language->language->name."\n";
            }
            return $list;
        }
        return 'No languages available for selected package!';
    }

    /**
     * 
     * Check if a language for a given package menu exists
     * 
     * @return  boolean
     */
    public function isPackageLanguageValid($packageId, $languageMenu)
    {
        $language = MarketPackageMessage::wherePackageId($packageId)->whereMenu($languageMenu)->first();
        return $language ? true : false;
    }

    /**
     * Get package language id using menu number
     * 
     * @return  int $id
     */
    public function getPackageLanguageId($packageId, $languageMenu)
    {
        $language = MarketPackageMessage::wherePackageId($packageId)->whereMenu($languageMenu)->first();
        return $language->language_id ?? null;
    }

    /**
     * Get frequencies of a package 
     * Format them as a menu list
     * 
     * @return string menu list  
     */
    public function getPackageFrequencies($packageId)
    {
        $list = '';
        $frequencies = MarketPackagePricing::wherePackageId($packageId)->orderBy('menu', 'ASC')->get();
        if (count($frequencies) > 0) {
            foreach ($frequencies as $frequency) {
                $list .= $frequency->menu.") ".$frequency->frequency."\n";
            }
            return $list;
        }
        return 'No frequencies available for selected package!';
    }

    /**
     * 
     * Check if a freq for a given package menu exists
     * 
     * @return  boolean
     */
    public function isPackageFrequencyValid($packageId, $frequencyMenu)
    {
        $frequency = MarketPackagePricing::wherePackageId($packageId)->whereMenu($frequencyMenu)->first();
        return $frequency ? true : false;
    }

    /**
     * Check if an active menu has many packages 
     * Check if package is valid using menu
     * 
     * @return  int $id
     */
    public function getPackageFrequency($packageId, $frequencyMenu)
    {
        $frequency = MarketPackagePricing::wherePackageId($packageId)->whereMenu($frequencyMenu)->first();
        return $frequency->frequency ?? null;
    }

    /**
     * Get list of enterprises of a given package
     * 
     * @return string 
     */
    public function getPackageEnterprises($packageId)
    {
        $package = MarketPackage::find($packageId);

        $items = '';
        if (count($package->enterprises)) {
            foreach ($package->enterprises as $enterprise) {
                $items .= $enterprise->enterprise->name.','; 
            }
        }
        return rtrim($items, ',');
    }

    public function getPackageCost($packageId, $frequency)
    {
        $cost = MarketPackagePricing::wherePackageId($packageId)->whereFrequency($frequency)->first();
        return $cost->cost ?? null;
    }

    /**
     * This function completes a market subscription by creating a MarketSubscription and a SubscriptionPayment record
     * using the session data and provided phone number.
     */
    public function completeMarketSubscription($sessionId, $phoneNumber)
    {
        // Retrieve the session data for the given session ID and phone number.
        $sessionData = UssdSessionData::whereSessionId($sessionId)->wherePhoneNumber($phoneNumber)->first();

        // Create an array containing the data for the new MarketSubscription record.
        $subscription_data = [
            'language_id'   => $sessionData->market_language_id,
            'phone'         => $sessionData->market_subscriber,
            'package_id'    => $sessionData->market_package_id,
            'frequency'     => $sessionData->market_frequency,
            'period_paid'   => $sessionData->market_frequency_count,
        ];

        // Create a new MarketSubscription record using the subscription_data array and assign it to $subscription variable.
        if ($subscription = MarketSubscription::create($subscription_data)) {
            // Get the payment API for the subscriber's phone number.
            $api = $this->getServiceProvider($sessionData->market_subscriber, 'payment_api');

            // Create an array containing the data for the new SubscriptionPayment record.
            $payment = [
                'market_subscription_id' => $subscription->id,
                'method'    => 'MM',
                'provider'  => $this->getServiceProvider($sessionData->market_subscriber, 'name'),
                'account'   => $sessionData->market_subscriber,
                'amount'    => $sessionData->market_cost,
                'sms_api'   => $this->getServiceProvider($sessionData->market_subscriber, 'sms_api'),
                'narrative' => $sessionData->market_frequency .' Market subscription',
                'reference_id' => $this->generateReference($api),
                'payment_api'  => $api,
                'status' => 'INITIATED'
            ];

            // Create a new SubscriptionPayment record using the payment array and return true if successful.
            if(SubscriptionPayment::create($payment)) return true;
        }

        // If an error occurred or data was missing, return false.
        return false;
    }

    /**
     * This function generates a unique reference ID for a subscription payment using the given payment API.
     * A do-while loop is used to ensure a unique reference ID is generated.
     * Generate a random number between 100 and 999999999 using mt_rand and remove any leading zeros using ltrim.
     * Check if there is already a subscription payment with the generated reference ID for the given payment API.
     * return Reference ID
     */
    public function generateReference($api){
      do{
        $reference_id = ltrim(mt_rand(100, 999999999), '0');
      }
      while (!is_null(SubscriptionPayment::whereReferenceId($reference_id)->wherePaymentApi($api)->first()));
          return $reference_id;      
    }
}
