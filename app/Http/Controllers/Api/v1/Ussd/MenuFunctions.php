<?php

namespace App\Http\Controllers\Api\v1\Ussd;

use DB;
use Log;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Settings\Season;
use App\Models\Settings\Language;
use App\Models\Settings\Country;
use App\Models\Ussd\UssdSession;
use App\Models\DistrictModel;
use App\Models\SubcountyModel;
use App\Models\ParishModel;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Region;
use App\Models\Ussd\UssdSessionData;
use App\Models\Ussd\UssdInsuranceList;
use App\Models\Settings\CountryProvider;

use App\Models\Market\MarketPackage;
use App\Models\Market\MarketPackageRegion;
use App\Models\Market\MarketSubscription;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackagePricing;
use App\Models\Ussd\UssdAdvisoryTopic;
use App\Models\Ussd\UssdLanguage;
use App\Models\Ussd\UssdAdvisoryQuestion;
use App\Models\Ussd\UssdEvaluationQuestion;
use App\Models\Ussd\UssdEvaluationSelection;
use App\Models\Ussd\UssdEvaluationQuestionOption;

use App\Models\Insurance\InsuranceSubscription;
use App\Models\Insurance\InsurancePremiumOption;
use App\Models\Insurance\Markup;

use App\Models\Weather\WeatherSubscription;
use App\Models\Payments\SubscriptionPayment;

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

    public function getRegionLanguageList($region_id)
    {
        $languages = Language::whereIn('id',function($query) use ($region_id) {
                                        $query->select('language_id')
                                                ->whereIn('package_id',function($query) use ($region_id) {
                                                    $query->select('package_id')
                                                            ->whereRegionId($region_id)
                                                            ->from(with(new MarketPackageRegion)->getTable());
                                                })
                                                ->whereIn('package_id',function($query) {
                                                    $query->select('id')->from(with(new MarketPackage)->getTable());
                                                })
                                                ->from(with(new MarketPackageMessage)->getTable());
                                    })                                    
                                    ->orderBy('name', 'ASC')
                                    ->get();

        $list = '';
        if (count($languages) > 0) {
            $count = 0;
            foreach ($languages as $language) {
                $list .= (++$count).") ".ucwords(strtolower($language->name))."\n";
            }
        }

        return $list;
    }

    public function getSelectedRegionLaguage($language_menu_no, $region_id)
    {
        $menu = intval($language_menu_no);

        $languages = Language::whereIn('id',function($query) use ($region_id) {
                                        $query->select('language_id')
                                                ->whereIn('package_id',function($query) use ($region_id) {
                                                    $query->select('package_id')
                                                            ->whereRegionId($region_id)
                                                            ->from(with(new MarketPackageRegion)->getTable());
                                                })
                                                ->whereIn('package_id',function($query) {
                                                    $query->select('id')->from(with(new MarketPackage)->getTable());
                                                })
                                                ->from(with(new MarketPackageMessage)->getTable());
                                    })
                                    ->orderBy('name', 'ASC')
                                    ->get();

        if($menu!=0) $language = $languages->skip($menu-1)->take(1)->first();

        return $language ?? null;
    }

    public function checkIfLanguageIsValid($language_name)
    {
        $language = Language::whereName($language_name)->first();
        return $language ? true : false;
    }

    /**
     * Get packages
     * Get enterprises of each package
     * Format them as a menu list
     * 
     * @return string menu list  
     */
    public function getPackageList($language_id)
    {
        $list = '';
        $packages = MarketPackage::whereStatus(true)
                                    
                                    ->whereIn('id',function($query) use ($language_id) {
                                        $query->select('package_id')->whereLanguageId($language_id)->from(with(new MarketPackageMessage)->getTable());
                                    })
                                    ->orderBy('name', 'ASC')->get();

        if (count($packages) > 0) {
            $count = 0;
            foreach ($packages as $package) {
                $items = '';
                if (count($package->enterprises)) {
                    foreach ($package->enterprises as $enterprise) {
                        $items .= $enterprise->enterprise->name.','; 
                    }
                }
                $list .= (++$count).") ".rtrim($items, ',')."\n";
                // $list .= $package->menu.") ".rtrim($items, ',')."\n";
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
    public function isPackageMenuValid($menu, $language_id)
    {
        $packages = MarketPackage::whereStatus(true)
                                    
                                    ->whereIn('id',function($query) use ($language_id) {
                                        $query->select('package_id')->whereLanguageId($language_id)->from(with(new MarketPackageMessage)->getTable());
                                    })
                                    ->orderBy('name', 'ASC')->get();

        $list = [];
        if (count($packages) > 0) {
            $count = 0;
            foreach ($packages as $package) {
                $list[] = ++$count;
            }
            return in_array($menu, $list);
        }

        return false;
    } 

    public function getSelectedPackage($package_menu_no)
    {
        $menu = intval($package_menu_no);

        $package = MarketPackage::whereStatus(true)->where('menu', $menu)->orderBy('name', 'ASC')->first();

        return $package ?? null;
    }


    public function getPackages(){

        $packages =  MarketPackage::with('ents')->whereStatus(true)
        ->orderBy('menu', 'ASC')->get();

        return $packages;

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
        $frequencies = MarketPackagePricing::wherePackageId($packageId)->orderByRaw('
                CASE `frequency`
                    WHEN "Trial" THEN 1
                    WHEN "Daily" THEN 2
                    WHEN "Weekly" THEN 3
                    WHEN "Monthly" THEN 4
                    WHEN "Yearly" THEN 5
                    ELSE 6
                END')
                ->get();

        if (count($frequencies) > 0) {
            $count = 0;
            foreach ($frequencies as $frequency) {
                $list .= (++$count).") ".$frequency->frequency."\n";
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
    public function isPackageFrequencyValid($packageId, $frequencyId)
    {
        $frequency = MarketPackagePricing::wherePackageId($packageId)->whereId($frequencyId)->first();
        return $frequency ? true : false;
    }

    /**
     * Check if an active menu has many packages 
     * Check if package is valid using menu
     * 
     * @return  int $id
     */
    public function getSelectedPackageFrequency($packageId, $frequency_menu_no)
    {
        $menu = intval($frequency_menu_no);

        $frequencies = MarketPackagePricing::wherePackageId($packageId)->orderByRaw('
                CASE `frequency`
                    WHEN "Trial" THEN 1
                    WHEN "Daily" THEN 2
                    WHEN "Weekly" THEN 3
                    WHEN "Monthly" THEN 4
                    WHEN "Yearly" THEN 5
                    ELSE 6
                END')
                ->get();
        
        if($menu!=0) $frequency = $frequencies->skip($menu-1)->take(1)->first();

        return $frequency ?? null;
    }

    /**
     * Get list of enterprises of a given package
     * 
     * @return string 
     */
    public function getPackageEnterprises($packageId)
    {
        $package = MarketPackage::with('ents')->where('id', $packageId)->first();
        
        info($package->ents);

        $items = '';
        if (count($package->ents)) {
            foreach ($package->ents as $enterprise) {
                $items .= $enterprise->name.','; 
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

        // Create a new MarketSubscription record using the subscription_data array and assign it to $subscription variable.
        if ($sessionData) {
            // Get the payment API for the subscriber's phone number.
            $api = $this->getServiceProvider($sessionData->market_subscriber, 'payment_api');

            // Create an array containing the data for the new SubscriptionPayment record.
            $payment = [
                'tool' => 'USSD',
                'market_session_id' => $sessionData->id,
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

    public function completeTrialMarketSubscription($sessionId, $phoneNumber){

        $sessionData = UssdSessionData::whereSessionId($sessionId)->wherePhoneNumber($phoneNumber)->first();

        if ($sessionData) {

            return true;
            
        }

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

    public function getRegionList()
    {
        $locations = Region::whereMenuStatus(TRUE)->orderBy('name', 'ASC')->get();

        $list = '';
        if (count($locations) > 0) {
            $count = 0;
            foreach ($locations as $region) {
                $list .= (++$count).") ".ucwords(strtolower($region->menu_name))."\n";
            }
        }

        return $list;
    }

    public function regionItemList($session, $phone, $chosenRegion)
    {
        // Retrieve the region model based on the chosen region ID
        $region = Region::find($chosenRegion);
        $optionMappings = [];

        // Check if the region exists
        if (!$region) {
            return "Invalid region.";
        }

        // Get the enterprises associated with the chosen region
        $enterprises = $region->enterprises()->orderBy('name', 'ASC')->get();

        // Check if any enterprises exist for the region
        if (count($enterprises) > 0) {
            $list = '';
            $count = 0;

            foreach ($enterprises as $enterprise) {
                $list .= (++$count) . ") " . $enterprise->name . "\n";
                $optionMappings[$count] = $enterprise->id;
            }

            $this->saveToField($session, $phone, "option_mappings", $optionMappings);

            return $list;
        } else {
            return "No supported crops found for this region.";
        }
    }

    public function getSelectedSeasonID($response)
    {
        $seasonList = $this->insuranceSeasonList();

        if (is_string($seasonList)) {
            // If $seasonList is a string, it means there are no seasons available
            return $seasonList;
        }
    
        return $seasonList[$userInput];
    }

    public function getSelectedRegionID($phone, $session, $response)
    {
        $saved_data = UssdSessionData::whereSessionId($session)
                                            ->wherePhoneNumber($phone)
                                            ->first();                                         

        $optionMappings = $saved_data->option_mappings;  
        
        $decodedOptionMappings = json_decode($optionMappings, true);

        // Validate user response
        if (!is_numeric($response)) {
            return "Invalid selection.";
        }

        return $decodedOptionMappings[$response];
    }

    public function getInsuranceRegionList($session, $phone)
    {
        $locations = Region::where([
            "menu_status" => 1
        ])->orderBy('name', 'ASC')->get();

        $optionMappings = [];

        $list = '';
        if (count($locations) > 0) {
            $count = 0;

            foreach ($locations as $region) {
                $list .= (++$count).") ".ucwords(strtolower($region->name))."\n";
                $optionMappings[$count] = $region->id;
            }
        }

        $this->saveToField($session, $phone, "option_mappings", $optionMappings);

        return $list;
    }

    public function getMostSimilarDistrict($district_name, $country_name)
    {
        $country = Country::whereName($country_name)->first();

        if ($country) {
            $locations = DistrictModel::where('name', 'LIKE', substr($district_name, 0, 1).'%')->get();

            $closestMatch = null;
            $lowestDistance = PHP_INT_MAX;

            foreach ($locations as $word) {
                $distance = levenshtein(strtolower($district_name), strtolower($word->name));
                if ($distance < $lowestDistance) {
                    $closestMatch = $word;
                    $lowestDistance = $distance;
                }
            }

            return $closestMatch;
        }

        return null;        
    }

    public function getDistrict($districtId, $param)
    {
        $location = DistrictModel::whereId($districtId)->first();

        return $location->$param ?? null;
    }

    public function getSubcountyList($districtId)
    {
        $locations = SubcountyModel::whereDistrictId($districtId)->orderBy('name', 'ASC')->get();

        $list = '';
        if (count($locations) > 0) {
            $count = 0;
            foreach ($locations as $subcounty) {
                $name = str_replace('TOWN COUNCIL', 'TC', $subcounty->name);
                $name = str_replace('DIVISION', 'DIV', $subcounty->name);
                $list .= (++$count).") ".ucwords(strtolower($name))."\n";
            }
        }

        return $list;
    }

    public function getParishList($subcountyId)
    {
        $locations = ParishModel::whereSubcountyId($subcountyId)->whereNotNull('lat')->whereNotNull('lng')->orderBy('name', 'ASC')->get();

        $list = '';
        if (count($locations) > 0) {
            $count = 0;
            foreach ($locations as $parish) {
                $list .= (++$count).") ".ucwords(strtolower($parish->name))."\n";
            }
        }

        return $list;
    }

    public function getSelectedRegion($region_menu_no)
    {
        $menu = intval($region_menu_no);

        $locations = Region::whereMenuStatus(TRUE)->orderBy('name', 'ASC')->get();

        if($menu!=0) $region = $locations->skip($menu-1)->take(1)->first();

        return $region ?? null;
    }

    public function getSelectedSubcounty($subcounty_menu_no, $districtId)
    {
        $menu = intval($subcounty_menu_no);

        $locations = SubcountyModel::whereDistrictId($districtId)->orderBy('name', 'ASC')->get();

        if($menu!=0) $subcounty = $locations->skip($menu-1)->take(1)->first();

        return $subcounty ?? null;
    }

    public function getSelectedParish($parish_menu_no, $subcountyId)
    {
        $menu = intval($parish_menu_no);

        $locations = ParishModel::whereSubcountyId($subcountyId)->orderBy('name', 'ASC')->get();

        if($menu!=0) $parish = $locations->skip($menu-1)->take(1)->first();

        return $parish ?? null;
    }

    public function checkIfRegionIsValid($region_name)
    {
        $location = Region::whereName($region_name)->first();
        return $location ? true : false;
    }

    public function checkIfDistrictIsValid($district_name)
    {
        $location = DistrictModel::whereName($district_name)->first();
        return $location ? true : false;
    }

    public function checkIfSubcountyIsValid($districtId, $subcounty_name)
    {
        $location = SubcountyModel::whereDistrictId($districtId)->whereName($subcounty_name)->first();
        return $location ? true : false;
    }

    public function checkIfParishIsValid($subcountyId, $parish_name)
    {
        $location = ParishModel::whereSubcountyId($subcountyId)->whereName($parish_name)->first();
        return $location ? true : false;
    }

    public function insuranceSeasonList()
    {
        $currentDate = now(); // Get the current date and time

        $seasons = Season::whereStatus(true)
                        ->whereDate('cut_off_date', '>=', $currentDate) // Filter by end date
                        ->orderBy('start_date', 'ASC')
                        ->get();

        if ($seasons->isNotEmpty()) {
            $list = '';
            $count = 0;
            foreach ($seasons as $season) {
                $list .= (++$count).") ".$season->name."\n";
            }
            return $list;
        } else {
            return "No seasons added yet."; // Return message when no seasons found
        }
    }

    public function checkIfSeasonIsValid($season_menu)
    {
        $seasons = Season::whereStatus(TRUE)->orderBy('start_date', 'ASC')->get();

        if (count($seasons) > 0) {
            $list = array();
            $count = 0;
            foreach ($seasons as $season) {
                $list[] = ++$count;
            }
        }

        return in_array($season_menu, $list) ? true : false;
    }

    public function getSeasonMenu($seasonId)
    {
        // Order the seasons by 'name' column in ascending order
        $seasons = Season::whereStatus(TRUE)->orderBy('start_date', 'asc')->get();

        // Find the position of the season with id 3
        $position = $seasons->search(function ($season) use ($seasonId) {
            return $season->id == $seasonId;
        });

        // $position will contain the position of the row with id 3 (0-based)
        if ($position !== false) {
            $position++; // Adding 1 to get the 1-based position
            return $position;
        } else {
            return null;
        }
    }

    // public function checkIfSeasonIsValid($season_menu_id)
    // {
    //     $season = Season::whereStatus(TRUE)->orderBy('start_date', 'ASC')->skip($season_menu_id-1)->first();
    //     return $season ? true : false;
    // }

    public function getSeasonDetail($menu, $param)
    {
        $season = Season::whereStatus(TRUE)->orderBy('start_date', 'ASC')->skip($menu-1)->first();
        return $season->$param ?? null;
    }

    public function getSeason($season_id, $param)
    {
        $season = Season::whereId($season_id)->first();
        return $season->$param ?? null;
    }

    public function getAcerage($input_text)
    {
        // Use PHP's built-in function intval() to convert the input text to an integer
        // If the input text is "1", it will be converted to the integer 1
        return intval($input_text);
    }

    public function getEnterprise($enterprise_id, $param)
    {
        $enterprise = Enterprise::whereId($enterprise_id)->first();
        return $enterprise->$param ?? null;
    }

    public function checkIfSeasonItemIsValid($item_menu)
    {
        $enterprise = InsurancePremiumOption::whereMenu($item_menu)->whereStatus(TRUE)->first();
        return $enterprise ? true : false;
    }

    public function getSelectedItemID($phone, $session, $response)
    {
        $saved_data = UssdSessionData::whereSessionId($session)
                                            ->wherePhoneNumber($phone)
                                            ->first();                                         

        $optionMappings = $saved_data->option_mappings;  
        
        $decodedOptionMappings = json_decode($optionMappings, true);

        // Validate user response
        if (!is_numeric($response)) {
            return "Invalid selection.";
        }

        return $decodedOptionMappings[$response];
    }

    public function getMarkup()
    {
        $markup = Markup::whereStatus(TRUE)->first();
        return $markup->amount ?? 3000;
    }
    
    public function getPremiumOptionDetails($enterprise_id, $param)
    {
        $enterprise = InsurancePremiumOption::whereEnterpriseId($enterprise_id)->whereStatus(TRUE)->first();
        return $enterprise->$param ?? null;
    }

    public function savePreviousItemList($sessionId, $phoneNumber)
    {
        $saved_data = UssdSessionData::whereSessionId($sessionId)
                                            ->wherePhoneNumber($phoneNumber)
                                            ->first();

        UssdInsuranceList::create([
            'ussd_session_data_id' => $saved_data->id,
            'insurance_enterprise_id' => $saved_data->insurance_enterprise_id,
            'insurance_acreage' => $saved_data->insurance_acreage,
            'insurance_sum_insured' => $saved_data->insurance_sum_insured,
            'insurance_premium' => $saved_data->insurance_premium,
        ]);
    }

    public function getInsuranceConfirmation($sessionId, $phoneNumber)
    {
        $saved_data = UssdSessionData::whereSessionId($sessionId)
                                            ->wherePhoneNumber($phoneNumber)
                                            ->first();
                                             
        $acerage     = $saved_data->insurance_acreage.' acre(s)';

        $enterprise_id  = $saved_data->insurance_enterprise_id;
        $enterpriseName = $this->getEnterprise($enterprise_id, 'name');

        $phone          = $saved_data->insurance_subscriber;                
        $sum_insured    = $saved_data->insurance_sum_insured;            
        $coverage    = $saved_data->insurance_coverage;
        $premium        = $saved_data->insurance_premium;

        if (count($saved_data->insurance_list) > 0) {
            foreach ($saved_data->insurance_list as $list) {
                $acerage .= ','.$list->insurance_acreage.'A';

                $enterprise_id  = $list->insurance_enterprise_id;
                $enterpriseName .= ','.$this->getEnterprise($enterprise_id, 'name');

                $sum_insured  += $list->insurance_sum_insured;
                $premium  += $list->insurance_premium;
            }
        }

        $this->saveToField($sessionId, $phoneNumber, 'insurance_amount', $premium);

        return "You are insuring ".$acerage." of ".$enterpriseName." at ".$coverage." coverage for the sum insured of  UGX".number_format($sum_insured).". You'll pay a premium of UGX".number_format(($premium)).". You're insured by MUA. Comfirm?";
    }

    /**
     * This function completes a insurance subscription by creating a InsuranceSubscription and a SubscriptionPayment record
     * using the session data and provided phone number.
     */
    public function completeInsuranceSubscription($sessionId, $phoneNumber)
    {
        // Retrieve the session data for the given session ID and phone number.
        $sessionData = UssdSessionData::whereSessionId($sessionId)->wherePhoneNumber($phoneNumber)->first();

        // Create a new Subscription record using the subscription_data array and assign it to $subscription variable.
        if ($sessionData) {
            // Get the payment API for the subscriber's phone number.
            $api = $this->getServiceProvider($sessionData->insurance_subscriber, 'payment_api');

            // Create an array containing the data for the new SubscriptionPayment record.
            $payment = [
                'tool' => 'USSD',
                'insurance_session_id' => $sessionData->id,
                'method'    => 'MM',
                'provider'  => $this->getServiceProvider($sessionData->insurance_subscriber, 'name'),
                'account'   => $sessionData->insurance_subscriber,
                'amount'    => $sessionData->insurance_amount,
                'sms_api'   => $this->getServiceProvider($sessionData->insurance_subscriber, 'sms_api'),
                'narrative' => $sessionData->insurance_acreage .'A of '.$sessionData->insurance_enterprise_id.' at '.$sessionData->insurance_coverage.' coverage  insurance subscription',
                'reference_id' => $this->generateReference($api),
                'payment_api'  => $api,
                'status'       => 'INITIATED'
            ];

            // Create a new SubscriptionPayment record using the payment array and return true if successful.
            if(SubscriptionPayment::create($payment)) return true;
        }

        // If an error occurred or data was missing, return false.
        return false;
    }

    public function getWeatherPeriodDetails($value, $count=0)
    {
        $details = (object) [];

        $weekly_cost = 1000;
        $annual_cost = 40000;

        if ($value == "1" || $value == "weekly") {
            $details->period    = "week";
            $details->frequency = "weekly";
            $details->cost = $count * $weekly_cost;
        }
        elseif ($value == "2" || $value == "monthly") {
            $details->period    = "month";
            $details->frequency = "monthly";
            $details->cost = 4 * $count * $weekly_cost;
        }
        elseif ($value == "6" || $value == "annually") {
            $details->period    = "year";
            $details->frequency = "annually";
            $details->cost = $count * $annual_cost;
        }

        return $details;
    }

    public function getAdvisoryTopics($position, $menu_id, $session_id){

        $language = UssdLanguage::select('id')->where('menu_id', $menu_id)->where('position', $position)->first();

        $data = [

            'language_id' => $language->id
        ];

        UssdSession::whereSessionId($session_id)->update(['data' => $data]);

        $topics =  UssdAdvisoryTopic::select('id', 'topic', 'position')->orderBy('position', 'asc')->where('ussd_language_id', $language->id)->get();

        return $topics;
    }

    public function getLanguage($input_text){
        $language = UssdLanguage::select('language')->where('position', $input_text)->first();
        return $language;
    }

    public function getLanguages($type){
        $languages = Language::whereNotNull('position')->where($type, "Yes")->select('id', 'name', 'position')->orderBy('position', 'asc')->get();
        return $languages;
    }

    public function getMenuLanaguages($menu_id){

        $languages = UssdLanguage::select('language', 'position')->where('menu_id', $menu_id)->orderBy('position', 'asc')->get();

        return $languages;
    }

    public function getSelectedLanguage($response, $session, $phone){
        $saved_data = UssdSessionData::whereSessionId($session)
                                            ->wherePhoneNumber($phone)
                                            ->first();                                         

        $optionMappings = $saved_data->option_mappings;  
        
        $decodedOptionMappings = json_decode($optionMappings, true);

        // Validate user response
        if (!is_numeric($response)) {
            return "Invalid selection.";
        }

        $languageID = $decodedOptionMappings[$response];

        $language = Language::find($languageID);

        if($language === null){
            return false;
        }
        else{
            return $language;
        }
    }

    public function checkIfUssdLanguageIsValid($input_text){


        $ussd_language = UssdLanguage::select('language', 'position')->where('position', $input_text)->first();

        if($ussd_language === null){

            return false;
        }
        else{
            return true;
        }

    }

    public function getAdvisoryQuestions($position, $session_id){

        $selected_language = UssdSession::where('session_id',$session_id)->select('data')->first();

        info($selected_language);

        $topic = UssdAdvisoryTopic::select('id')->where('position', $position)->where('ussd_language_id', $selected_language->data['language_id'])->first();

        $data = [

            'language_id' => $selected_language->data['language_id'],
            'topic_id' => $topic->id
        ];

        UssdSession::whereSessionId($session_id)->update(['data' => $data]);

        $question = UssdAdvisoryQuestion::with(['options' => function ($q) {
            $q->orderBy('position', 'asc');
        }])->where('ussd_advisory_topic_id', $topic->id)->first(); 

        return $question;
    }

    public function getEvaluationQuestions($position, $session_id){
        
        $selected_language = UssdSession::where('session_id',$session_id)->select('data')->first();

        $question = UssdEvaluationQuestion::with(['options' => function ($q) {
            $q->orderBy('position', 'asc');
        }])->where('position', $position)->where('ussd_language_id', $selected_language->data['language_id'])->first();

        info($question);

        return $question;
    }

    public function getSessionLanguage($session_id){

        $selected_language = UssdSession::where('session_id',$session_id)->select('data')->first();

        $ussd_language = UssdLanguage::select('language')->where('id', $selected_language->data['language_id'])->first();

        if($ussd_language === null){

            return false;
        }
        else{
            return $ussd_language;
        }
    }

    public function saveEvaluationAnswer($session_id, $current_question, $input_text){

        $selected_language = UssdSession::where('session_id',$session_id)->select('data')->first();

        $question = UssdEvaluationQuestion::where('position', $current_question)->where('ussd_language_id', $selected_language->data['language_id'])->first();


        $selection = new UssdEvaluationSelection();
        $selection->session_id = $session_id;
        $selection->user_selection = $input_text;
        $selection->ussd_evaluation_question_id = $question->id;
        $selection->save();

    }

    /**
     * This function completes a weather subscription by creating a WeatherSubscription and a SubscriptionPayment record
     * using the session data and provided phone number.
     */
    public function completeWeatherSubscription($sessionId, $phoneNumber)
    {
        // Retrieve the session data for the given session ID and phone number.
        $sessionData = UssdSessionData::whereSessionId($sessionId)->wherePhoneNumber($phoneNumber)->first();

        if ($sessionData) {
            // Get the payment API for the subscriber's phone number.
            $api = $this->getServiceProvider($sessionData->weather_subscriber, 'payment_api');

            // Create an array containing the data for the new SubscriptionPayment record.
            $payment = [
                'tool' => 'USSD',
                'weather_session_id' => $sessionData->id,
                'method'    => 'MM',
                'provider'  => $this->getServiceProvider($sessionData->weather_subscriber, 'name'),
                'account'   => $sessionData->weather_subscriber,
                'amount'    => $sessionData->weather_amount,
                'sms_api'   => $this->getServiceProvider($sessionData->weather_subscriber, 'sms_api'),
                'narrative' => $sessionData->weather_frequency .' Weather subscription',
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
}
