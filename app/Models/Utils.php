<?php

namespace App\Models;

use App\Models\Farmers\FarmerGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Zebra_Image;
use Berkayk\OneSignal\OneSignalClient;

class Utils
{

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
        try{
            $response = $client->request('GET', "https://me.agrinetug.net/api/export_groups/{$external_id}?token=*psP@3ksMMw7");
        }catch(\Throwable $th){
            return;
        }

        if($response == null){
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
            break;
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
}
