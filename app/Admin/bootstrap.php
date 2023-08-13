<?php

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
use Encore\Admin\Facades\Admin;
use PHPUnit\Framework\Constraint\Count;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::css(url('/assets/css/bootstrap.css'));
Admin::css('/assets/css/styles.css');

$counties = CountyModel::all();
foreach ($counties as $county) {
    $affect = SubcountyModel::where([
        'county_id' => $county->id
    ])->update([
        'district_id' => $county->district_id,
    ]);
    echo "Affected: " . $affect . " rows in " . $county->name . "<br>";
}
die();
