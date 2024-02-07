<?php

namespace App\Models;

use App\Models\Farmers\FarmerGroup;
use App\Services\Payments\PaymentServiceFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Zebra_Image;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Support\Facades\Mail;

class Utils
{


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
        $amount = 500;
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

        $narrative = "Omulimisa payment.";
        $amount = 500;
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

        $phone = Utils::prepare_phone_number($phone);
        if (Utils::phone_number_is_valid($phone) == false) {
            return 'Invalid phone number';
        }
        $sms = urlencode($sms);
        $url = '';
        $url .= "?spname=mulimisa";
        $url .= "&sppass=mul1m1s4";
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
            throw $th;
        }

        if ($body == null) {
            return 'Failed to send request 2';
        }

        $data = json_decode($body);

        if ($data == null) {
            return 'Failed to decode response 1';
        }

        if (!isset($data->Failed)) {
            return 'Failed to get status ' . $body;
        }
        if (!isset($data->Total)) {
            return 'Total not set ' . $body;
        }

        if (((int)$data->Failed) > 0) {
            return 'Failed sms sent is greater than 0 4';
        }
        if (((int)$data->Total) < 1) {
            return 'Total sms sent is less than 1 5';
        }
        return 'success';
    }


    static function syncGroups()
    {
        $lastGroup = FarmerGroup::orderBy('external_id', 'desc')->first();
        $external_id = 0;
        if ($lastGroup != null) {
            if ($lastGroup->external_id != null) {
                $external_id = $lastGroup->external_id;
            }
        }

        //http grt request to url using guzzlehttp 
        $client = new \GuzzleHttp\Client();
        $response = null;
        try {
            $response = $client->request('GET', "https://me.agrinetug.net/api/export_groups/{$external_id}?token=*psP@3ksMMw7");
        } catch (\Throwable $th) {
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

        /* 
        array:49 [▼ 
  
  "group_bank_account" => false
  "financial_institution_id" => null
  "account_number" => null
  "account_name" => null
  "new_group" => false
  "received_other_ngo_support" => false
  "participation_of_collective_marketing" => true
  "is_member_of_dairy_cooperative" => false
  "cooperative_id" => null
  "have_a_market_map" => false
  "have_a_storage_facility" => false
  "have_modern_dairy_equipment" => false
  "type_of_dairy_equipment_ownership" => null
  "have_constitution_or_law" => true
  "is_group_involved_in_selling_related_dairy_products" => false
  "does_group_keep_records" => true
  "records_description" => "Sales and VSL"
  "last_updated_market_map" => "2023-11-27"
  "have_a_business_plan" => true
  "last_updated_business_plan" => "2023-11-27"
  "involved_in_vsla_services" => true
  "vsla_services_description" => null
  "enterprise_id" => 9
  "loan_received_in_last_year" => false
  "loan_financial_institution_id" => null
  "registration_status" => "Registered"
  "registration_number" => "656"
  "" => 3085

  "group_position_id" => 4

  "group_representative_mobile_money" => false
  "received_mastercard_support" => false
  "mastercard_program_id" => null
  "user_id" => 8
  "deleted_at" => null
  "created_at" => "2023-11-10T07:35:06.000000Z"
  "updated_at" => "2023-11-27T10:51:49.000000Z"
  "non_government_organisation_id" => null
  "non_government_organisations" => []
  "supporting_ngos_ids" => ""
  "enterprise_text" => "Milk Vending & Retailing"
  "group_position_text" => "Member"
]
        */
        foreach ($groups as $key => $ext) {
            $old = FarmerGroup::where([
                'external_id' => $ext['id']
            ])->first();
            if ($old != null) {
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
                $new->save();
            } catch (\Throwable $th) {
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
        $headings = 'M-OMULIMISA',
        $data = null,
        $url = null,
        $buttons = null,
        $schedule = null,
    ) {
        try {
            $client = new OneSignalClient(
                env('ONESIGNAL_APP_ID'),
                env('ONESIGNAL_REST_API_KEY'),
                env('USER_AUTH_KEY')
            );
            $client->addParams(
                [
                    'android_channel_id' => '7ae6ea3e-3d7b-4a4c-aca4-b07634205ec3',
                    'large_icon' => env('APP_URL') . '/assets/images/logo.png',
                    'small_icon' => 'logo',
                ]
            )
                ->sendNotificationToExternalUser(
                    $msg,
                    "$receiver",
                    $url = $url,
                    $data = $data,
                    $buttons = $buttons,
                    $schedule = $schedule,
                    $headings = $headings
                );
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }


        return;
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

    public static function my_resp($type, $data)
    {
        header('Content-type: text/plain');
        if ($type == 'audio') {
            $menu = OnlineCourseMenu::where([
                'name' => $data
            ])->first();
            if ($menu != null) {
                $url = asset('storage/' . $menu->english_audio);
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

    public static function quizz_menu($topic)
    {
        header('Content-type: text/plain');
        $lesson_url = asset('storage/' . $topic->video_url);
        echo
        '<Response>
            <Play url="' . $lesson_url . '" />
            <GetDigits timeout="40" numDigits="1" >
                <Say>Please enter your quiz answer.</Say>
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



    public static function lesson_menu($type, $data, $topic, $student = null)
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


    public static function my_resp_digits($type, $data, $student = null)
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
                <GetDigits timeout="40" numDigits="1" >
                    <Play url="' . $url . '" />
                </GetDigits>
                <Say>We did not get your input number. Good bye</Say>
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
}
