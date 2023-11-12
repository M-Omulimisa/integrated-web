<?php

namespace App\Services\IdValidations;

use Illuminate\Http\Request;
use App\Models\IdValidations\PhoneValidation;
use App\Services\Payments\YoUganda;

class PhoneValidationService
{
    public static function initiate($phone)
    {
        $value = str_replace('+', '', $phone);
        if (strlen($value) == 12 && substr($value, 0, 3) == "256") {
            // Create a new YoUganda instance with Yo! Payments Username and Password

            if (config('payments.services.yo_ug.phoneapi')=='test') {
                $url = config('payments.services.yo_ug.test.url');
                $username = config('payments.services.yo_ug.test.username');
                $password = config('payments.services.yo_ug.test.password');
            }
            else{
                $url = config('payments.services.yo_ug.url');
                $username = config('payments.services.yo_ug.username');
                $password = config('payments.services.yo_ug.password');
            }

            // logger([$url, $username, $password]);

            $yopay = new YoUganda(); 

            $yopay->set_URL($url);
            $yopay->set_username($username);
            $yopay->set_password($password);

            return $yopay->ac_get_msisdn_kyc_info($phone);
        }
        else{
          $phone                  = new Request;
          $phone->status          = (string) 'FAILED';
          $phone->error_code      = (string) '422';
          $phone->error_message   = (string) 'Invalid phone number';
          $phone->message_payload = $phone->surname = $phone->first_name = $phone->middle_name = null;
          $phone->phone_status    = 'WRONG';

          return $phone;
        }
    }

    public function saveResults(PhoneValidation $validation, $result)
    {
        $validation->update([
            'error_code'      => $result->error_code,
            'error_message'   => $result->error_message,
            'message_payload' => $result->message_payload,
            'phone_status'    => $result->phone_status,
            'phone_surname'   => $result->surname,
            'phone_firstname' => $result->first_name,
            'phone_middlename'  => $result->middle_name,
            'status'            => $result->status,
            'mno_authority'     => getProviderCode($validation->phonenumber)
        ]);

        return $validation;
    }

    public static function bill(PhoneValidation $validation, $user)
    {
      # code
    }

    public static function generatePhoneReference()
    {
        do {
            //generate a random string
            $reference_number = 'PHN'.strtoupper(generate_random_str(0, 9, 12));
        } //check if the token already exists and if it does, try again
        while (PhoneValidation::whereReference($reference_number)->first());

        return $reference_number;
    }
}
