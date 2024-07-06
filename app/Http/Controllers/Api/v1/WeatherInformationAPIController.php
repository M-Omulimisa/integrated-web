<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Weather\WeatherSubscription;

class WeatherInformationAPIController extends Controller
{
    use ApiResponser;

    public function checkIfSubscribed(Request $r)
    {
        if (!isset($r->id) || $r->id === null) {
            return $this->error('Phone number is missing.');
        }

        $subscriptions = WeatherSubscription::where('status', true)
            ->where('phone', $r->id)
            ->get();

        if ($subscriptions->isEmpty()) {
            return $this->error('No active subscriptions found for this phone number.');
        }

        return $this->success($subscriptions, 'Subscriptions found and payment status checked.', 200);
    }
}
