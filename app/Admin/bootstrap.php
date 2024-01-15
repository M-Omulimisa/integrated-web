<?php
use Illuminate\Support\Facades\Schema;

/* 
curl -X GET "https://api.app.outscraper.com/maps/search-v3?query=restaurants%2C%20Manhattan%2C%20NY%2C%20USA&limit=3&async=false" -H  "X-API-KEY: YOUR-API-KEY" 
*/
//curl -X GET "https://api.app.outscraper.com/maps/search-v3?query=restaurants%2C%20Manhattan%2C%20NY%2C%20USA&limit=3&async=false" -H  "X-API-KEY: YOUR-API-KEY"
//http request to get the data

/* $curl = curl_init();
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.app.outscraper.com/maps/search-v3?query=IUIU%2C%20UG%2C%2Uganda&limit=3&async=false",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "X-API-KEY: ZjM4YmY3NjU5ZmY2NGIzNDgxMGM3NDQyODg2N2EyOWJ8Yjk0ZDVjOGJkNw"
    ),
)); 
$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response);
dd($data);
echo $response;
die(); */



/* $table1 = Schema::getColumnListing('products');
$table2 = ["id","name","metric","currency","description","summary","price_1","price_2","feature_photo","rates","date_added","date_updated","user","category","sub_category","supplier","url","status","in_stock","keywords","p_type","local_id","updated_at","created_at"];

foreach ($table2 as $key => $val) {
    if (!in_array($val, $table1)) {
        echo '$table->text("'.$val.'")->nullable();'."<br>";
    }
}
die();  */


use App\Models\ParishModel;
use App\Models\Utils;
use Dflydev\DotAccessData\Util;

if (!Utils::isLocalhost()) {
    Utils::syncGroups();
}
/* 
$parishes = ParishModel::where('lat', null)
    ->limit(100)
    ->get();

foreach ($parishes as $parish) {
    $_parish = $parish->name;
    $_subcounty = "";
    $_county = "";
    $_district = "";
    if ($parish->subcounty != null) {
        $_subcounty = $parish->subcounty->name;
        if ($parish->subcounty->county != null) {
            $_county = $parish->subcounty->county->name;
        }
        if ($parish->subcounty->district != null) {
            $_district = $parish->subcounty->district->name;
        }
    }
    $keyword = Str::lower("Uganda, $_district,  $_county, $_subcounty, $_parish");
    echo $keyword . "<br>";
    $latLog =  get_gps($keyword);
    if ($latLog != null) {
        dd($latLog);
    }
}





function get_gps($keyword)
{
    $googleAPiKey = 'AIzaSyBbXYigCGL7Du8zAiJ9ZWP1a0mw1zOJevw';
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$keyword,Uganda&key=$googleAPiKey";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($curl);
    curl_close($curl);
    if (($output != null) && $output != false) {
        dd($output);
        if (strlen($output) > 2) {
            $json = json_decode($output);
            if ($json->results != null) {
                if (is_array($json->results)) {
                    if ($json->results[0] != null) {
                        $obj = $json->results[0];
                        if ($obj->geometry != null) {
                            if ($obj->geometry->location != null) {
                                $lat = $obj->geometry->location->lat;
                                $lng = $obj->geometry->location->lng;
                                return [
                                    'lat' => $lat,
                                    'lng' => $lng
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}
die(); */

/** 
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use App\Models\CountyModel;
use App\Models\SubcountyModel;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use PHPUnit\Framework\Constraint\Count;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::css(url('/assets/css/bootstrap.css'));
Admin::css('/assets/css/styles.css');

/* $u = Administrator::find(1);
if($u!=null){
    $u->email = 'admin@gmail.com';
    $u->username = 'admin@gmail.com';
    $u->password = password_hash('4321', PASSWORD_DEFAULT);
    $u->save();
    die("done 1");
}
 */
/* $counties = CountyModel::all();
foreach ($counties as $county) {
    $affect = SubcountyModel::where([
        'county_id' => $county->id
    ])->update([
        'district_id' => $county->district_id,
    ]);
    echo "Affected: " . $affect . " rows in " . $county->name . "<br>";
}
die();
 */