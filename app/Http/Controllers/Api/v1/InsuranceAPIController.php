<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class InsuranceAPIController extends Controller
{
    use ApiResponser;

    public function getServiceProvider($phoneNumber, $param)
    {
        $dialing_code = substr($phoneNumber, 0, 5);
        $provider = \App\Models\Settings\CountryProvider::where('codes', 'LIKE', '%' . $dialing_code . '%')->first();
        return $provider ? $provider->$param : null;
    }

    public function getMarkup()
    {
        $markup = \App\Models\Insurance\Markup::whereStatus(TRUE)->first();

        return $this->success($markup->amount, 'Success');
    }

    public function generateReference($api)
    {
        do {
            $reference_id = ltrim(mt_rand(100, 999999999), '0');
        } while (!is_null(\App\Models\Payments\SubscriptionPayment::whereReferenceId($reference_id)->wherePaymentApi($api)->first()));
        return $reference_id;
    }

    public function submitSubscriptionRequest(Request $r)
    {
        try {
            \App\Models\Ussd\UssdSessionData::create([
                'session_id'                                                => $r->session_id,
                'phone_number'                                              => $r->phone_number,
                "insurance_subscrption_for"                                 => "self",
                "insurance_enterprise_id"                                   => $r->enterprise,
                "insurance_amount"                                          => $r->amount,
                'module'                                                    => "insurance",
                "insurance_subscriber"                                      => $r->phone_number,
                "insurance_acreage"                                         => $r->acreage,
                "insurance_sum_insured"                                     => $r->sum_insured,
                "insurance_premium"                                         => $r->premium,
                "markup"                                                    => $r->markup,
                "insurance_coverage"                                        => $r->coverage,
                "confirmation_message"                                      => 1,
                "insurance_region_id"                                       => $r->region_id,

                "agent_id"                                                  => $r->agent_id,
                "insurer_name"                                              => $r->insurer_name,
                "insurance_type"                                            => $r->insurance_type,
                "surname"                                                   => $r->surname,
                "payment_phone"                                             => $r->payment_phone,
                "paid"                                                      => $r->paid,
                "completed"                                                 => $r->completed,
                "pending"                                                   => $r->pending,
                "cancelled"                                                 => $r->cancelled,
                "national_id"                                               => $r->national_id,
                "village_id"                                                => $r->village_id,
                "driving_license"                                           => $r->driving_license,
                "passport"                                                  => $r->passport,
                "payment_phone"                                             => $r->payment_phone,
                "email"                                                     => $r->email,
                "lat"                                                       => $r->lat,
                "long"                                                      => $r->long,
                "category"                                                  => $r->category,
                "other_name"                                                => $r->other_name,
                "agent_sale"                                                => $r->agent_sale,
                "environments"                                              => $r->environments,
                "animal_production_business_duration"                       => $r->animal_production_business_duration,
                "profession"                                                => $r->profession,
                "animals_in_posession_duration"                             => $r->animals_in_posession_duration,
                "animals_keeping_purpose"                                   => $r->animals_keeping_purpose,
                "loan"                                                      => $r->loan,
                "selected_animals"                                          => $r->selected_animals,
                "animals_lost"                                              => $r->animals_lost,
                "selected_products"                                         => $r->selected_products,
                "causes_of_death"                                           => $r->causes_of_death,
                "animal_health"                                             => $r->animal_health,
                "animal_illness"                                            => $r->animal_illness,
                "animal_treatment"                                          => $r->animal_treatment,
                "animal_contagious"                                         => $r->animal_contagious,
                "risks"                                                     => $r->risks,
                "conviction"                                                => $r->conviction,
                "additional_info"                                           => $r->additional_info,
                "management"                                                => $r->management,
                "supervisory"                                               => $r->supervisory,
                "security"                                                  => $r->security,
                "laborer"                                                   => $r->laborer,
                "paid"                                                      => $r->paid,
                "sub_county"                                                => $r->sub_county,
                "parish"                                                    => $r->parish,
                "village"                                                   => $r->village,
                "district"                                                  => $r->district,
                "animals_lost"                                              => $r->animals_lost
            ]);

            // Retrieve the session data for the given session ID and phone number.
            $sessionData = \App\Models\Ussd\UssdSessionData::whereSessionId($r->sessionID)->wherePhoneNumber($r->phoneNumber)->first();

            // Create a new Subscription record using the subscription_data array and assign it to $subscription variable.
            echo $r->insurance_type;

            if ($r->insurance_type == "crop" || $r->insurance_type == null) {
                if ($sessionData != null) {
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
                        'narrative' => 'Insurance subscription',
                        'reference_id' => $this->generateReference($api),
                        'payment_api'  => $api,
                        'status'       => 'INITIATED'
                    ];

                    // Create a new SubscriptionPayment record using the payment array and return true if successful.
                    if (\App\Models\Payments\SubscriptionPayment::create($payment)) {
                        return $this->success("All good", 'Success');
                    } else {
                        return $this->error("Something went wrong. Please contact system admins.");
                    }
                } else {
                    return $this->error("Error saving session data");
                }
            } else {
                return $this->success("All good", 'Success');
            }
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function get_premium_option_details(Request $r)
    {
        $enterprise = \App\Models\Insurance\InsurancePremiumOption::whereEnterpriseId($r->id)->whereStatus(TRUE)->first();

        return $this->success($enterprise, 'Success');
    }

    public function regions()
    {
        $items = \App\Models\Settings\Region::where([
            "menu_status" => 1
        ])->orderBy('name', 'ASC')->get();

        return $this->success($items, 'Success');
    }

    public function get_region_supported_crops(Request $r)
    {
        $region = \App\Models\Settings\Region::find($r->id);
        $optionMappings = [];

        // Check if the region exists
        if (!$region) {
            return "Invalid region.";
        }

        // Get the enterprises associated with the chosen region
        $enterprises = $region->enterprises()->orderBy('name', 'ASC')->get();

        return $this->success($enterprises, 'Success');
    }

    public function seasons()
    {
        $currentDate = now(); // Get the current date and time

        $items = \App\Models\Settings\Season::whereStatus(true)
            ->whereDate('cut_off_date', '>=', $currentDate) // Filter by end date
            ->orderBy('start_date', 'ASC')
            ->get();

        return $this->success($items, 'Success');
    }

    public function premium_options(Request $r)
    {
        $items = \App\Models\Insurance\InsurancePremiumOption::whereEnterpriseId($r)->whereStatus(TRUE)->first();
        return $this->success($items, 'Success');
    }
}
