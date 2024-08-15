<?php

namespace App\Models;

use App\Models\Farmers\Farmer;
use App\Models\Farmers\FarmerGroup;
use App\Models\Market\Market;
use App\Models\Market\MarketSubscription;
use App\Models\Weather\WeatherSubscription;
use App\Services\Payments\PaymentServiceFactory;
use App\Services\Weather\TomorrowApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Zebra_Image;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class Utils
{
    public static function get_ai_answer($query)
    {
        if (strlen($query) < 3) {
            return '';
        }
        $url = 'http://20.246.158.238:8080/ask';
        $data = [
            'prompt' => $query
        ];

        $guzzle = new \GuzzleHttp\Client();
        $response = null;
        try {
            $response = $guzzle->post($url, [
                'json' => $data
            ]);
        } catch (\Throwable $th) {
            return '';
        }
        if ($response == null) {
            return '';
        }
        $body = $response->getBody();
        $data = json_decode($body);
        if ($data == null) {
            return '';
        }

        //check if not array
        if (!is_array($data)) {
            return '';
        }
        //check if $data[0] is not set
        if (!isset($data[0])) {
            return '';
        }
        if (!isset($data[0]->message)) {
            return '';
        }
        if ($data[0]->message == null) {
            return '';
        }
        return $data[0]->message;
    }
    public static function short($string, $length = 100)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length) . '...';
        } else {
            return $string;
        }
    }

    public static function system_boot()
    {
        self::process_market_subs(false);
        self::renew_messages();
        $farmers = Farmer::where('user_account_processed', '!=', 'Yes')->get();
        foreach ($farmers as $key => $value) {
            if ($key > 100) {
                break;
            }
            Farmer::process($value);
        }
    }

    public static function renew_messages()
    {
        foreach (
            MarketSubscription::where(['renew_message_sent' => 'No'])
                ->orderBy('created_at', 'desc')
                ->get() as $key => $value
        ) {
            if ($key > 1000) {
                break;
            }
            //check if skipped and skip
            if ($value->skip_renew_message == 'Yes') {
                continue;
            }
            $value->send_renew_message();
        }

        foreach (
            WeatherSubscription::where(['renew_message_sent' => 'No'])
                ->orderBy('created_at', 'desc')
                ->get() as $key => $value
        ) {
            if ($key > 1000) {
                break;
            }
            $value->send_renew_message();
        }
    }
    public static function greet()
    {
        //according to the time of the day
        $hour = date('H');
        if ($hour < 12) {
            return "Good morning";
        } else if ($hour < 17) {
            return "Good afternoon";
        } else {
            return "Good evening";
        }
    }

    public static function file_upload($file)
    {
        if ($file == null) {
            return '';
        }
        //GET FILE EXTENSION FILE NAME FRO FILE
        $file_extension = $file->getClientOriginalExtension();
        //get file extension
        $file_extension = $file->getClientOriginalExtension();
        $file_name = $file->getClientOriginalName();
        $public_path = public_path() . "/storage/files";
        $file->move($public_path, $file_name);
        $url = 'files/' . $file_name;
        return $url;
    }

    //public static function email_is_valid
    public static function email_is_valid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //mail sender
    public static function mail_sender($data)
    {
        try {
            Mail::send(
                'mails/mail-1',
                [
                    'body' => $data['body'],
                    'title' => $data['subject']
                ],
                function ($m) use ($data) {
                    $m->to($data['email'], $data['name'])
                        ->subject($data['subject']);
                    $m->from(env('MAIL_FROM_ADDRESS'), $data['subject']);
                }
            );
        } catch (\Throwable $th) {
            $msg = 'failed';
            throw $th;
        }
    }


    public static function payment_status_test()
    {
        $PaymentFactory = new PaymentServiceFactory();
        $service = $PaymentFactory->getService('yo_ug');
        if (!$service) {
            throw new \Exception("Failed to get payment service");
        }
        $service->set_URL();
        $service->set_username();
        $service->set_password();

        //$faild_reference_id = "PaoHpb4vpfkZ9hzdxR04PdtJR4H6ot0ZGurv6qdOOVdHEcjhxuCz4XMZhSOF2fdh61074cec31f11636c82e2b5783ffcb4f";
        $faild_reference_id = "Ef8BFyJ3NhULq2vBNTVu47GgnP1XV1vP0CxsGlixN0cMOLYahQBkGsi57KjqUJaf0ba161438d0d8c4d877f1f03541379a1";
        $faild_reference_id = "Oh145te1z62t2pZ7tbLic2NNKBuIxuadAC7B8YYNMBGQmlcKdBJuE7QXAknvVD4h47fffd5e9d22f8e0d1602012c943dcd7";
        $my_reference_id = "464988113";
        $response = $service->getTransactionStatus(
            $faild_reference_id,
            $my_reference_id,
        );
        dd($response);

        die("success");
    }

    public static function payment_test()
    {
        $PaymentFactory = new PaymentServiceFactory();
        $service = $PaymentFactory->getService('yo_ug');
        if (!$service) {
            throw new \Exception("Failed to get payment service");
        }
        $service->set_URL();
        $service->set_username();
        $service->set_password();

        $phone = "256783204665";
        $phone = "256706638494";
        $amount = 1000;
        $narrative = "Test payment";
        $reference_id = "464988113";
        $response = $service->depositFunds(
            $phone,
            $amount,
            $narrative,
            $reference_id
        );
        dd($response);

        die("success");
    }


    public static function init_payment($phone_number, $amount, $reference_id)
    {
        $phone_number = Utils::prepare_phone_number($phone_number);
        if (Utils::phone_number_is_valid($phone_number) == false) {
            throw new \Exception("Invalid phone numbe $phone_number");
        }
        $phone_number = str_replace("+", "", $phone_number);
        $amount = (int)($amount);
        if ($amount < 500) {
            throw new \Exception("Amount must be greater than UGX 499");
        }

        /* $test_resp = new \stdClass();
        $test_resp->Status = "OK";
        $test_resp->StatusCode = "1";
        $test_resp->StatusMessage = "";
        $test_resp->TransactionStatus = "PENDING";
        $test_resp->TransactionReference = "PaoHpb4vpfkZ9hzdxR04PdtJR4H6ot0ZGurv6qdOOVdHEcjhxuCz4XMZhSOF2fdh61074cec31f11636c82e2b5783ffcb4f";
        return $test_resp; */


        $PaymentFactory = new PaymentServiceFactory();
        $service = null;

        try {
            $service = $PaymentFactory->getService('yo_ug');
        } catch (\Throwable $th) {
            throw new \Exception("Failed to get payment service because " . $th->getMessage());
        }

        if ($service == null) {
            throw new \Exception("Failed to get payment service");
        }
        $service->set_URL();
        $service->set_username();
        $service->set_password();

        $narrative = "M-Omulimisa payment.";
        //$amount = 1000;
        $response = null;
        try {
            $response = $service->depositFunds(
                $phone_number,
                $amount,
                $narrative,
                $reference_id
            );
        } catch (\Throwable $th) {
            throw new \Exception("Failed to initiate payment because " . $th->getMessage());
        }
        if ($response == null) {
            throw new \Exception("Failed to initiate payment");
        }
        return $response;
    }


    public static function payment_status_check($token, $payment_reference_id)
    {
        $PaymentFactory = new PaymentServiceFactory();
        $service = $PaymentFactory->getService('yo_ug');
        if (!$service) {
            throw new \Exception("Failed to get payment service");
        }
        $service->set_URL();
        $service->set_username();
        $service->set_password();

        //$faild_reference_id = "PaoHpb4vpfkZ9hzdxR04PdtJR4H6ot0ZGurv6qdOOVdHEcjhxuCz4XMZhSOF2fdh61074cec31f11636c82e2b5783ffcb4f";
        // $faild_reference_id = "Ef8BFyJ3NhULq2vBNTVu47GgnP1XV1vP0CxsGlixN0cMOLYahQBkGsi57KjqUJaf0ba161438d0d8c4d877f1f03541379a1";
        // $faild_reference_id = "Oh145te1z62t2pZ7tbLic2NNKBuIxuadAC7B8YYNMBGQmlcKdBJuE7QXAknvVD4h47fffd5e9d22f8e0d1602012c943dcd7";
        // $my_reference_id = "464988113";
        try {
            $response = $service->getTransactionStatus($token, $payment_reference_id);
        } catch (\Throwable $th) {
            throw new \Exception("Failed to check payment status because " . $th->getMessage());
        }
        if ($response == null) {
            throw new \Exception("Failed to check payment status");
        }
        return $response;
    }

    public static function phone_number_is_valid($phone_number)
    {
        $phone_number = Utils::prepare_phone_number($phone_number);
        if (substr($phone_number, 0, 4) != "+256") {
            return false;
        }

        //check if contains numbers only
        if (!ctype_digit(substr($phone_number, 1, strlen($phone_number)))) {
            return false;
        }

        if (strlen($phone_number) != 13) {
            return false;
        }

        return true;
    }
    public static function prepare_phone_number($phone_number)
    {
        $original = $phone_number;
        //$phone_number = '+256783204665';
        //0783204665
        if (strlen($phone_number) > 10) {
            $phone_number = str_replace("+", "", $phone_number);
            $phone_number = substr($phone_number, 3, strlen($phone_number));
        } else {
            if (substr($phone_number, 0, 1) == "0") {
                $phone_number = substr($phone_number, 1, strlen($phone_number));
            }
        }
        if (strlen($phone_number) != 9) {
            return $original;
        }
        return "+256" . $phone_number;
    }


    public static  function send_sms($phone, $sms)
    {



        $outbox = new SMSOutbox();
        $outbox->phone = $phone;
        $outbox->sms = $sms;
        $outbox->status = 'pending';



        $test_numbers = [
            '256783204665',
            '+256783204665',
            '256706638494',
            '+256706638494',
        ];

        //check if phone number received same message in the last 5 minutes
        $last = SMSOutbox::where([
            'phone' => $phone,
            'sms' => $sms
        ])->orderBy('created_at', 'desc')->first();
        if ($last != null) {
            $now = Carbon::now();
            $last = Carbon::parse($last->created_at);
            $diff = $now->diffInMinutes($last);
            if ($diff < 10) {
                $outbox->status = 'cancelled';
                $outbox->reason = 'Duplicate message. Sent in the last 10 minutes';
                $outbox->save();
                return 'success';
            }
        }
        //check if phone number is in test numbers
        if (in_array($phone, $test_numbers)) {
            $outbox->status = 'cancelled';
            $outbox->reason = 'Test number';
            $outbox->save();
            return 'success';
        }


        try {
            $_phone = Utils::prepare_phone_number($phone);
            $u = User::where('phone', $_phone)->first();
            if ($u == null) {
                $u = User::where('phone', $phone)->first();
            }
            if ($u == null) {
                $_phone = str_replace("+", "", $_phone);
                $u = User::where('phone_number', $_phone)->first();
            }
            if ($u != null) {
                if ($u->id > 0) {
                    Utils::sendNotification2([
                        'msg' => $sms,
                        'headings' => 'Welcome to Market Information Service',
                        'receiver' => $u->id,
                        'type' => 'text',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        if (Utils::isLocalhost()) {
            $outbox->status = 'sent';
            $outbox->reason = 'Localhost';
            $outbox->save();
            return true;
        }
        $phone = Utils::prepare_phone_number($phone);
        if (Utils::phone_number_is_valid($phone) == false) {
            $outbox->status = 'failed';
            $outbox->reason = 'Invalid phone number';
            $outbox->save();
            return 'Invalid phone number';
        }
        $sms = urlencode($sms);
        $url = '';
        $url .= "?spname=mulimisa";
        $url .= "&sppass=Dev@Team1";
        $url .= "&numbers=$phone";
        $url .= "&msg=$sms";
        $url .= "&type=json";

        $url = "https://sms.dmarkmobile.com/v2/api/send_sms/" . $url;

        //use guzzle to make the request 
        $body = null;
        try {
            //use use curl to make the request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $body = curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $th) {
            //throw $th;
            $outbox->status = 'failed';
            $outbox->reason = 'Failed to send request. ' . $th->getMessage();
            $outbox->save();
            return 'Failed to send request';
        }

        if ($body == null) {
            $outbox->status = 'failed';
            $outbox->reason = 'Failed to send request 2';
            $outbox->save();
            return 'Failed to send request 2';
        }

        $data = json_decode($body);

        if ($data == null) {
            $outbox->status = 'failed';
            $outbox->reason = 'Failed to decode response';
            $outbox->save();
            return 'Failed to decode response 1';
        }

        if (!isset($data->Failed)) {
            $outbox->status = 'failed';
            $outbox->reason = 'Failed to get status';
            $outbox->save();
            return 'Failed to get status ' . $body;
        }
        if (!isset($data->Total)) {
            $outbox->status = 'failed';
            $outbox->reason = 'Total not set';
            $outbox->save();
            return 'Total not set ' . $body;
        }

        if (((int)$data->Failed) > 0) {
            $outbox->status = 'failed';
            $outbox->reason = 'Failed sms sent is greater than 0';
            $outbox->save();
            return 'Failed sms sent is greater than 0';
            return 'Failed sms sent is greater than 0 4';
        }
        if (((int)$data->Total) < 1) {
            $outbox->status = 'failed';
            $outbox->reason = 'Total sms sent is less than 1';
            $outbox->save();
            return 'Total sms sent is less than 1 5';
        }
        $outbox->status = 'sent';
        $outbox->reason = 'Success';
        $outbox->save();
        return 'success';
    }


    static function syncGroups()
    {
        return;
        $lastGroup = FarmerGroup::orderBy('external_id', 'desc')->first();
        $external_id = 0;
        if ($lastGroup != null) {
            if ($lastGroup->external_id != null) {
                $external_id = $lastGroup->external_id;
            }
        }
        //get last created at time from now in minutes
        if ($lastGroup != null) {
            $now = Carbon::now();
            $last = Carbon::parse($lastGroup->created_at);
            $diff = $now->diffInMinutes($last);
            if ($diff < 5) {
                // return;
            }
        }

        $page = 0;
        $last = FarmerGroup::orderBy('created_at', 'desc')->first();
        if ($last != null) {
            $page = ((int)($last->id_photo_back));
        }
        $page = $page + 1;


        //http grt request to url using guzzlehttp 
        $client = new \GuzzleHttp\Client();
        $response = null;
        try {
            $response = $client->request('GET', "https://me.agrinetug.net/api/export_groups/{$external_id}?token=*psP@3ksMMw7&page={$page}");
        } catch (\Throwable $th) {
            throw $th;
            return;
        }


        if ($response == null) {
            return;
        }

        $data = null;

        try {
            $data = json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            $data = null;
        }
        if ($data == null) {
            return;
        }

        if (!isset($data['data'])) {
            return;
        }
        $groups = $data['data'];
        if ($groups == null) {
            return;
        }
        if (isset($groups['data'])) {
            $groups = $groups['data'];
        }
        foreach ($groups as $key => $ext) {

            $old = FarmerGroup::where([
                'external_id' => $ext['id']
            ])->first();
            if ($old != null) {
                echo "old" . $ext['farmer_group'] . " - " . $ext['id'] . "<br>";
                continue;
            }

            try {
                $new = new FarmerGroup();
                $new->external_id = $ext['id'];
                $new->name = $ext['farmer_group'];
                $new->country_id = '3578d4de-da91-43f2-b630-35b3017b67ec';
                $new->organisation_id = '57159775-b9e0-41ce-ad99-4fdd6ed8c1a0';
                $new->code = $ext['farmer_group_code'];
                $new->address = $ext['email_address'];
                $new->group_leader = $ext['group_representative_first_name'] . " " . $ext['group_representative_last_name'];
                $new->group_leader_contact = $ext['group_representative_contact'];
                $new->establishment_year = $ext['establishment_year'];
                $new->registration_year = $ext['establishment_year'];
                $new->location_id = $ext['village_id'];
                $new->status = 'Active';
                $new->id_photo_front = 'External';
                $new->id_photo_back = $page;
                $new->save();
                echo $new->id . ". SAVED " . $new->farmer_group . "<br>";
            } catch (\Throwable $th) {
                echo 'FAILED: ' . $ext['farmer_group'] . " - " . $th->getMessage() . "<br><hr>";
                continue;
            }
        }
    }


    static function syncFarmers()
    {
        $lastGroup = Farmer::orderBy('user_id', 'desc')->first();
        $external_id = 0;
        if ($lastGroup != null) {
            if ($lastGroup->external_id != null) {
                $external_id = $lastGroup->external_id;
            }
        }
        //get last created at time from now in minutes
        if ($lastGroup != null) {
            $now = Carbon::now();
            $last = Carbon::parse($lastGroup->created_at);
            $diff = $now->diffInMinutes($last);
            if ($diff < 5) {
                // return;
            }
        }

        $page = 0;
        $last = Farmer::orderBy('imported_page_number', 'desc')->first();
        if ($last != null) {
            $page = ((int)($last->imported_page_number));
            $page++;
        }

        /* if ($last != null) {
            $page = ((int)($last->sheep_count));
            if ($page == 0) {
                $page = 1;
            }
        }
        $page = $page + 1;
        if ($page > 300) {
            $page = 300;
            if ($last != null) {
                $last->sheep_count = 300;
                $last->save();
            }
        }
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } */

        //http grt request to url using guzzlehttp 
        $client = new \GuzzleHttp\Client();
        $response = null;
        try {
            // $link = "https://me.agrinetug.net/api/export_participants/{$external_id}?token=*psP@3ksMMw7&page={$page}";
            // dd($link);
            $link = "https://me.agrinetug.net/api/export_participants/{$external_id}?token=*psP@3ksMMw7&page={$page}";
            echo $link;
            $response = $client->request('GET', $link);
        } catch (\Throwable $th) {
            throw $th;
            return;
        }
        echo "<pre>";
        print_r($response);
        echo "</pre>";


        if ($response == null) {
            throw new \Exception("Failed to get response");
            return;
        }

        $data = null;

        try {
            $data = json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            $data = null;
        }

        if ($data == null) {
            return;
        }

        if (!isset($data['data'])) {
            return;
        }
        $groups = $data['data'];
        if ($groups == null) {
            return;
        }
        if (isset($groups['data'])) {
            $groups = $groups['data'];
        }
        foreach ($groups as $key => $ext) {
            $farmer = Farmer::where([
                'user_id' => $ext['id']
            ])->first();
            if ($farmer != null) {
                $farmer = Farmer::where([
                    'external_id' => $ext['id']
                ])->first();
            }
            $phone = $ext['participant_contact'];
            $phone = Utils::prepare_phone_number($phone);

            if ($farmer == null && (strlen($phone) > 5)) {
                if (Utils::phone_number_is_valid($phone)) {
                    $farmer = Farmer::where([
                        'phone' => $phone
                    ])->first();
                }
            }
            if ($farmer == null && (strlen($phone) > 5)) {
                $farmer = Farmer::where([
                    'phone_number' => $phone
                ])->first();
            }

            if ($farmer == null) {
                if (Utils::phone_number_is_valid($phone) && (strlen($phone) > 5)) {
                    $farmer = Farmer::where([
                        'phone' => $ext['participant_contact']
                    ])->first();
                }
            }

            if (strlen($phone) < 5) {
                $phone = $ext['participant_contact'];
            }

            $isNew = 'Existing';
            if ($farmer == null) {
                $farmer = new Farmer();
                $isNew = 'New';
            }
            $farmer->external_id = $ext['id'];

            $farmer->first_name = $ext['first_name'];
            $farmer->last_name = $ext['last_name'];
            $farmer->other_name =  $ext['other_name'];
            $group = FarmerGroup::where('external_id', $ext['farmer_group_id'])->first();
            if ($group != null) {
                $farmer->farmer_group_id = $group->id;
                $farmer->organisation_id =  0;
            } else {
                $farmer->farmer_group_id = 0;
                $farmer->organisation_id = $ext['farmer_group_id'];
            }
            $farmer->village_id = $ext['village_id'];
            $farmer->household_size = $ext['household_size'];
            $farmer->gender = $ext['gender'];
            $farmer->phone = $phone;
            $farmer->phone_number = $phone;
            $farmer->status = 'Active';
            $farmer->process_status = 'No';
            $farmer->imported_page_number = $page;
            $farmer->external_group_id = $ext['farmer_group_id'];
            $farmer->participant_code = $ext['participant_code'];
            $farmer->household_details = $ext['household_details'];
            $farmer->disability_type = $ext['disability_type'];
            $farmer->number_of_pwd_in_house_hold = $ext['number_of_pwd_in_house_hold'];
            $farmer->household_head_relationship = $ext['household_head_relationship'];
            $farmer->household_head_year_of_birth = $ext['household_head_year_of_birth'];
            $farmer->household_head_occupation = $ext['household_head_occupation'];
            $farmer->received_other_ngo_support = $ext['received_other_ngo_support'];
            $farmer->min_income_range = $ext['min_income_range'];
            $farmer->max_income_range = $ext['max_income_range'];
            $farmer->own_a_smart_phone = $ext['own_a_smart_phone'];
            $farmer->village_id = $ext['village_id'];
            $farmer->is_house_hold_head = $ext['is_house_hold_head'];
            $farmer->participant_mobile_money = $ext['participant_mobile_money'];
            $farmer->employment_status = $ext['employment_status'];
            $farmer->created_by = $ext['created_by'];
            $farmer->highest_education_level = $ext['highest_education_level'];
            $farmer->main_economic_activity = $ext['main_economic_activity'];
            $farmer->number_of_children = $ext['number_of_children'];
            $farmer->next_of_kin_first_name = $ext['next_of_kin_first_name'];
            $farmer->next_of_kin_last_name = $ext['next_of_kin_last_name'];
            $farmer->next_of_kin_contact = $ext['next_of_kin_contact'];
            $farmer->is_imported = "Yes";
            $farmer->imported_processed = "No";
            $farmer->imported_page_number = $page;
            $farmer->year_of_birth = $ext['year_of_birth'];
            $farmer->created_at = $ext['created_at'];
            $farmer->marital_status = $ext['marital_status'];
            $farmer->is_pwd = $ext['pwd_status'];
            $farmer->is_refugee = $ext['refugee_status'];
            $farmer->home_gps_latitude = $ext['gps_latitude'];
            $farmer->home_gps_longitude = $ext['gps_longitude'];

            try {
                $farmer->save();
                echo ("<hr>{$farmer->id}.  $isNew => " . $farmer->first_name . " " . $farmer->last_name . ". PAGE: " . $page . "<br>");
            } catch (\Throwable $th) {
                echo 'FAILED: SAVE FARMER BECAUSE => ' . $th->getMessage() . "<br>";
                continue;
            }
        }
    }


    static function isLocalhost()
    {
        if (!isset($_SERVER['SERVER_NAME'])) {
            return false;
        }
        $serverName = $_SERVER['SERVER_NAME'];
        $httpHost = $_SERVER['HTTP_HOST'];

        // Check if the server name or HTTP host contains "localhost"
        if (strpos($serverName, 'localhost') !== false || strpos($httpHost, 'localhost') !== false) {
            return true;
        }

        // Check for common local IP addresses (127.0.0.1 and ::1)
        $localIPs = array('127.0.0.1', '::1');
        if (in_array($serverName, $localIPs) || in_array($httpHost, $localIPs)) {
            return true;
        }

        return false;
    }

    public static function sendNotification(
        $msg,
        $receiver,
        $headings = 'M-Omulimisa',
        $data = null,
        $url = null,
        $buttons = null,
        $schedule = null
    ) {
        try {
            $client = new OneSignalClient(
                env('ONESIGNAL_APP_ID'),
                env('ONESIGNAL_REST_API_KEY'),
                env('USER_AUTH_KEY')
            );
            $client
                ->sendNotificationToExternalUser(
                    $msg,
                    $receiver,
                    $url = $url,
                    $data = $data,
                    $buttons = $buttons,
                    $schedule = $schedule,
                    $headings = $headings
                );
        } catch (\Throwable $th) {
            //throw $th;
            // die("failed");
            // throw $th;
        }


        return;
    }

    public static function sendNotification2(
        $data = [
            'msg' => null,
            'receiver' => null,
            'headings' => 'M-OMULIMISA',
            'data' => null,
            'url' => null,
            'buttons' => null,
            'schedule' => null,
            'type' => 'text'
        ]
    ) {
        if ($data['msg'] == null) {
            return true;
            throw new \Exception("Message is required");
        }
        if ($data['receiver'] == null) {
            return true;
            throw new \Exception("Receiver is required");
        }


        /* 
        */
        $ONESIGNAL_APP_ID = '2007b43a-6c6b-4cab-b619-b367e5c184fb';
        $ONESIGNAL_REST_API_KEY = 'ZTNlODJhYTktMjVjOC00NjVkLTgwZmEtYzU3YTI3MGNkNzY1';
        // OneSignal API Key and App ID
        $apiKey = $ONESIGNAL_REST_API_KEY;
        $appId = $ONESIGNAL_APP_ID;
        $userId = $data['receiver'];


        /* if ($userId == null) {
            throw new \Exception("User id is required");
        }
        if ($userId == '') {
            throw new \Exception("User id is required");
        } */

        $receivers = [];
        if (is_array($userId)) {
            foreach ($userId as $key => $value) {
                $receivers[] = trim($value) . '';
            }
        } else {
            $receivers[] = trim($userId) . '';
        }

        $message_created = false;
        if (isset($data['message_created'])) {
            if ($data['message_created'] == 'Yes') {
                $message_created = true;
            }
        }

        if (!$message_created) {
            foreach ($receivers as $rec) {
                $my_rec = User::find($rec);
                if ($my_rec == null) {
                    continue;
                }
                $not = new NotificationMessage();
                $not->user_id = $my_rec->id;
                $not->notification_campaign_id = 0;
                $not->title = isset($data['headings']) ? $data['headings'] : 'M-OMULIMISA';
                $not->phone_number = $my_rec->phone;
                $not->email = $my_rec->email;
                $not->sms_body = isset($data['msg']) ? $data['msg'] : 'No message';
                $not->short_description = isset($data['msg']) ? $data['msg'] : 'No message';
                $not->body = isset($data['msg']) ? $data['msg'] : 'No message';
                $not->image = isset($data['big_picture']) ? $data['big_picture'] : '';
                $not->url = isset($data['url']) ? $data['url'] : '';
                $not->notification_seen = 'No';
                $not->notification_sent = 'Yes';
                $not->sms_sent = 'Yes';
                try {
                    $not->save();
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }


        /*     
        	
        	
        type	
        priority	
        status	
        ready_to_send	
        send_notification	
        send_email	
        send_sms	
        sheduled_at	
        email_sent	
        	
        	
        notification_seen_time	
        	
        */

        // Notification data
        $notificationData = [
            'app_id' => $appId,
            'contents' => ['en' => $data['msg']],
            'headings' => ['en' => $data['headings'] . ' - M-Omulimisa'],
            'external_user_id' => $receivers,
            'include_external_user_ids' => $receivers,
        ];

        if (isset($data['big_picture']) && $data['big_picture'] != null && $data['big_picture'] != '' && strlen($data['big_picture']) > 2) {
            $notificationData['big_picture'] = $data['big_picture'];
            $notificationData['small_icon'] = $data['big_picture'];
        }
        //$notificationData['big_picture'] = $data['big_picture'];
        //if url is set
        if (isset($data['url']) && $data['url'] != null && $data['url'] != '' && strlen($data['url']) > 2) {
            $notificationData['url'] = $data['url'];
        }

        //if data is set
        if (isset($data['data']) && $data['data'] != null && $data['data'] != '' && strlen($data['data']) > 2) {
            $notificationData['data'] = $data['data'];
        }
        //if buttons is set
        if (isset($data['buttons']) && $data['buttons'] != null && $data['buttons'] != '' && strlen($data['buttons']) > 2) {
            $notificationData['buttons'] = $data['buttons'];
        }
        //if schedule is set
        if (isset($data['schedule']) && $data['schedule'] != null && $data['schedule'] != '' && strlen($data['schedule']) > 2) {
            $notificationData['send_after'] = $data['schedule'];
        }
        //if type is set
        if (isset($data['type']) && $data['type'] != null && $data['type'] != '' && strlen($data['type']) > 2) {
            $notificationData['content_type'] = $data['type'];
        }

        // Initialize Guzzle client
        $client = new \GuzzleHttp\Client();
        $response = null;

        try {
            // Send POST request to OneSignal API
            $response = $client->post('https://onesignal.com/api/v1/notifications', [
                'headers' => [
                    'Authorization' => 'Basic ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'body' => json_encode($notificationData),

                ],
                'json' => $notificationData,
            ]);
        } catch (\Throwable $th) {
            $response = null;
            throw $th;
        }
        if ($response == null) {
            throw new \Exception("Failed to send notification because response is null");
        }

        // Get the response body
        $responseBody = $response->getBody();

        // Decode the JSON response
        $responseData = json_decode($responseBody, true);
        if ($responseData == null) {
            throw new \Exception("Failed to send notification because response data is null");
        }

        if (!isset($responseData['id'])) {
            throw new \Exception("Failed to send notification because response data id is not set");
        }

        return $responseData['id'];
    }

    public static function get_user_id($request = null)
    {
        if ($request == null) {
            return 0;
        }
        $header = (int)($request->header('user'));
        if ($header < 1) {
            $header = (int)($request->user);
        }
        if ($header < 1) {
            return 0;
        }
        return $header;
    }

    public static function response($data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        $resp['status'] = "1";
        $resp['code'] = "1";
        $resp['message'] = "Success";
        $resp['data'] = null;
        if (isset($data['status'])) {
            $resp['status'] = $data['status'] . "";
            $resp['code'] = $data['status'] . "";
        }
        if (isset($data['message'])) {
            $resp['message'] = $data['message'];
        }
        if (isset($data['data'])) {
            $resp['data'] = $data['data'];
        }
        return $resp;
    }

    public static function my_date_time($t)
    {
        $c = Carbon::parse($t);
        if ($t == null) {
            return $t;
        }
        return $c->format('d M, Y - h:m a');
    }

    public static function to_date_time($raw)
    {
        return Utils::my_date_time($raw);
    }

    public static function my_date($t)
    {
        $c = Carbon::parse($t);
        if ($t == null) {
            return $t;
        }
        return $c->format('d M, Y');
    }

    public static function month($t)
    {
        $c = Carbon::parse($t);
        if ($t == null) {
            return $t;
        }
        return $c->format('M - Y');
    }

    public static function my_time_ago($t)
    {
        $c = Carbon::parse($t);
        if ($t == null) {
            return $t;
        }
        return $c->diffForHumans();
    }


    public static function docs_root()
    {
        $r = $_SERVER['DOCUMENT_ROOT'] . "";

        if (!str_contains($r, 'home/')) {
            $r = str_replace('/public', "", $r);
            $r = str_replace('\public', "", $r);
        }

        if (!(str_contains($r, 'public'))) {
            $r = $r . "/public";
        }


        /* 
         "/home/ulitscom_html/public/storage/images/956000011639246-(m).JPG
        
        public_html/public/storage/images
        */
        return $r;
    }


    public static function isImageFile($filename)
    {
        // Allowed image MIME types
        $allowedTypes = array(
            IMAGETYPE_JPEG,
            IMAGETYPE_PNG,
            IMAGETYPE_GIF,
            IMAGETYPE_BMP,
            IMAGETYPE_WEBP,
            // Add any other image types you want to support
        );

        // Get the MIME type of the file
        $imageType = exif_imagetype($filename);

        // Check if the MIME type corresponds to an image
        return in_array($imageType, $allowedTypes);
    }




    public static function upload_images_1($files, $is_single_file = false)
    {

        ini_set('memory_limit', '-1');
        if ($files == null || empty($files)) {
            return $is_single_file ? "" : [];
        }
        $uploaded_images = array();
        foreach ($files as $file) {

            if (
                isset($file['name']) &&
                isset($file['type']) &&
                isset($file['tmp_name']) &&
                isset($file['error']) &&
                isset($file['size'])
            ) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_name = time() . "-" . rand(100000, 1000000) . "." . $ext;
                //$destination = 'public/storage/images/' . $file_name; 
                $destination = Utils::docs_root() . '/storage/images/' . $file_name;

                $res = move_uploaded_file($file['tmp_name'], $destination);
                if (!$res) {
                    continue;
                }
                //$uploaded_images[] = $destination;
                $uploaded_images[] = $file_name;
            }
        }

        $single_file = "";
        if (isset($uploaded_images[0])) {
            $single_file = $uploaded_images[0];
        }


        return $is_single_file ? $single_file : $uploaded_images;
    }




    public static function upload_images_2($files, $is_single_file = false)
    {

        ini_set('memory_limit', '-1');
        if ($files == null || empty($files)) {
            return $is_single_file ? "" : [];
        }
        $uploaded_images = array();
        foreach ($files as $file) {

            if (
                isset($file['name']) &&
                isset($file['type']) &&
                isset($file['tmp_name']) &&
                isset($file['error']) &&
                isset($file['size'])
            ) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_name = time() . "-" . rand(100000, 1000000) . "." . $ext;
                $destination = Utils::docs_root() . '/storage/images/' . $file_name;

                try {
                    $res = move_uploaded_file($file['tmp_name'], $destination);
                    //die("successss ".$destination);
                } catch (\Exception $e) {
                    $res = false;
                    die("failed " . $e->getMessage());
                }

                if (!$res) {
                    continue;
                }
                //$uploaded_images[] = $destination;
                $uploaded_images[] = $file_name;
            }
        }

        $single_file = "";
        if (isset($uploaded_images[0])) {
            $single_file = $uploaded_images[0];
        }


        return $is_single_file ? $single_file : $uploaded_images;
    }


    public static function create_thumbail($params = array())
    {

        ini_set('memory_limit', '-1');

        if (
            !isset($params['source']) ||
            !isset($params['target'])
        ) {
            return [];
        }



        if (!file_exists($params['source'])) {
            $img = url('assets/images/cow.jpeg');
            return $img;
        }


        $image = new Zebra_Image();

        $image->auto_handle_exif_orientation = true;
        $image->source_path = "" . $params['source'];
        $image->target_path = "" . $params['target'];


        if (isset($params['quality'])) {
            $image->jpeg_quality = $params['quality'];
        }

        $image->preserve_aspect_ratio = true;
        $image->enlarge_smaller_images = true;
        $image->preserve_time = true;
        $image->handle_exif_orientation_tag = true;

        $img_size = getimagesize($image->source_path); // returns an array that is filled with info





        $image->jpeg_quality = 50;
        if (isset($params['quality'])) {
            $image->jpeg_quality = $params['quality'];
        } else {
            $image->jpeg_quality = Utils::get_jpeg_quality(filesize($image->source_path));
        }
        if (!$image->resize(0, 0, ZEBRA_IMAGE_CROP_CENTER)) {
            return $image->source_path;
        } else {
            return $image->target_path;
        }
    }

    public static function get_jpeg_quality($_size)
    {
        $size = ($_size / 1000000);

        $qt = 50;
        if ($size > 5) {
            $qt = 10;
        } else if ($size > 4) {
            $qt = 10;
        } else if ($size > 2) {
            $qt = 10;
        } else if ($size > 1) {
            $qt = 11;
        } else if ($size > 0.8) {
            $qt = 11;
        } else if ($size > .5) {
            $qt = 12;
        } else {
            $qt = 15;
        }

        return $qt;
    }

    public static function process_images_in_backround()
    {
        $url = url('api/process-pending-images');
        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
        try {
            $data =  file_get_contents($url, null, $ctx);
            return $data;
        } catch (Exception $x) {
            return "Failed $url";
        }
    }

    public static function process_images_in_foreround()
    {
        $imgs = Image::where([
            'thumbnail' => null
        ])->get();

        foreach ($imgs as $img) {
            $thumb = Utils::create_thumbail([
                'source' => 'public/storage/images/' . $img->src,
                'target' => 'public/storage/images/thumb_' . $img->src,
            ]);
            if ($thumb != null) {
                if (strlen($thumb) > 4) {
                    $img->thumbnail = $thumb;
                    $img->save();
                }
            }
        }
    }

    public static function my_resp($type, $data, $student = null)
    {
        header('Content-type: text/plain');
        if ($type == 'audio') {
            $menu = OnlineCourseMenu::where([
                'name' => $data
            ])->first();
            if ($menu != null) {
                $url = asset('storage/' . $menu->english_audio);

                if ($student != null) {
                    $audio_1 = null;
                    try {
                        $audio_1 = $student->get_menu_audio_url($menu);
                    } catch (\Throwable $th) {
                        $audio_1 = null;
                    }

                    if ($audio_1 != null && strlen($audio_1) > 4) {
                        $url = $audio_1;
                    }
                }

                echo
                '<Response>
                    <Play url="' . $url . '" />
                </Response>';
                die();
            }
        }
        echo
        '<Response>
            <Say voice="en-US-Standard-C" playBeep="false" >' . $data . '</Say>
        </Response>';
        die();
    }

    public static function quizz_menu($topic, $prefixContent = '')
    {
        header('Content-type: text/plain');
        $lesson_url = asset('storage/' . $topic->video_url);
        echo
        '<Response>
            ' . $prefixContent . '
            <GetDigits timeout="40" numDigits="1" >
                <Play url="' . $lesson_url . '" />
            </GetDigits>
            <Say>We did not get your answer. Good bye</Say>
        </Response>';
        die();
    }

    public static function question_menu($topic, $student = null)
    {
        header('Content-type: text/plain');

        $menu = OnlineCourseMenu::where([
            'name' => 'Record Question'
        ])->first();
        if ($menu != null) {
            $url = asset('storage/' . $menu->english_audio);

            if ($student != null) {
                $audio_1 = null;
                try {
                    $audio_1 = $student->get_menu_audio_url($menu);
                } catch (\Throwable $th) {
                }
                if ($audio_1 != null && strlen($audio_1) > 4) {
                    $url = $audio_1;
                }
            }
            echo
            '<Response>
                <Record finishOnKey="*" maxLength="120" trimSilence="true" playBeep="true">
                    <Play url="' . $url . '" />
                </Record>
            </Response>';
            die();
        }

        echo
        '<Response>
            <Record finishOnKey="*" maxLength="120" trimSilence="true" playBeep="true">
                <Say voice="en-US-Standard-C" playBeep="false" >Please record your question.</Say>
            </Record>';
        die();
    }



    public static function lesson_menu($type, $data, $topic, $student = null, $prefixContent = '')
    {
        header('Content-type: text/plain');

        $lesson_url = asset('storage/' . $topic->audio_url);

        if ($type == 'audio') {
            $menu = OnlineCourseMenu::where([
                'name' => $data
            ])->first();
            if ($menu != null) {
                $url = asset('storage/' . $menu->english_audio);
                $audio_1 = null;

                if ($student != null) {
                    try {
                        $audio_1 = $student->get_menu_audio_url($menu);
                    } catch (\Throwable $th) {
                    }
                    if ($audio_1 != null && strlen($audio_1) > 4) {
                        $url = $audio_1;
                    }
                }

                echo
                '<Response>
                ' . $prefixContent . '
                <Play url="' . $lesson_url . '" />
                <GetDigits timeout="20" numDigits="1" >
                    <Play url="' . $url . '" />
                </GetDigits>
                <Say>We did not get your input number. Good bye.</Say>
            </Response>';
                die();
            }
        }
        echo     '<Response>
        <GetDigits timeout="40" >
            <Say voice="en-US-Standard-C" playBeep="false" >' . $data . '</Say>
            </GetDigits>
            <Say>We did not get your input number. Good bye</Say>
        </Response>';
        die();
    }


    public static function my_resp_digits($type, $data, $student = null, $prefixContent = '')
    {
        header('Content-type: text/plain');
        if ($type == 'audio') {
            $menu = OnlineCourseMenu::where([
                'name' => $data
            ])->first();


            if ($menu != null) {

                $url = asset('storage/' . $menu->english_audio);
                $audio_1 = null;
                if ($student != null) {
                    try {
                        $audio_1 = $student->get_menu_audio_url($menu);
                    } catch (\Throwable $th) {
                        die($th->getMessage());
                    }
                    if ($audio_1 != null && strlen($audio_1) > 4) {
                        $url = $audio_1;
                    }
                }

                echo
                '<Response>
                ' . $prefixContent . '
                <GetDigits timeout="20" numDigits="1" >
                    <Play url="' . $url . '" />
                </GetDigits>
                <Say>We did not get your input number. Good bye</Say>
            </Response>';
                die();
            }
        }
    }

    public static function create_column($table, $new_cols)
    {
        try {
            $colls_of_table = Schema::getColumnListing($table);
            foreach ($new_cols as $new_col) {
                if (!isset($new_col['name'])) {
                    continue;
                }
                if (!isset($new_col['type'])) {
                    continue;
                }
                if (!in_array($new_col['name'], $colls_of_table)) {
                    Schema::table($table, function (Blueprint $t) use ($new_col) {
                        $name = $new_col['name'];
                        $type = $new_col['type'];
                        $default = null;
                        if (isset($new_col['default'])) {
                            $default = $new_col['default'];
                        }
                        $t->$type($name)->default($default)->nullable();
                    });
                }
            }
        } catch (\Throwable $th) {
            //throw $th->getMessage();
        }
    }


    public static function process_market_subs($process_all)
    {
        $subs = [];
        if ($process_all) {
            MarketSubscription::where([])->get();
        } else {
            MarketSubscription::where('is_processed', '!=', 'Yes')->get();
        }
        $data = [];
        foreach ($subs as $key => $sub) {
            $sub->process_subscription();
        }
    }


    public static function process_weather_subs($process_all)
    {

        $subs = \App\Models\Weather\WeatherSubscription::where([])->orderBy('created_at', 'desc')->limit(1000)->get();
        $data = [];
        foreach ($subs as $key => $sub) {
            $sub->process_subscription();
        }
    }
}
