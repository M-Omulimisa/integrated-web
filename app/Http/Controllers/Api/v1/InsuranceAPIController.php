<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Region;
use App\Models\Settings\Season;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Encore\Admin\Auth\Database\Administrator;
use Exception;
use Illuminate\Http\Request;

class ImsuranceAPIController extends Controller
{
    use ApiResponser;

    public function regions()
    {
        $items = \App\Models\Settings\Region::orderBy('name', 'ASC')->get();
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
        $items = \App\Models\Settings\Season::where([])->get();
        return $this->success($items, 'Success');
    }

    public function premium_options(Request $r)
    {
        $items = \App\Models\Settings\InsurancePremiumOption::whereEnterpriseId($r)->whereStatus(TRUE)->first();
        return $this->success($items, 'Success');
    }

    public function get_markup()
    {
        $markup = Markup::whereStatus(TRUE)->first();
        return $this->success($markup->amount ?? 3000, 'Success');
    }
}