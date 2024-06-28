<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Weather\WeatherSubscription;

class WeatherInformationAPIController extends Controller
{
    public function checkIfSubscribed(Request $r)
    {
        if (!isset($r->id) || $r->id == null) {
            return $this->error('Phone number is missing.');
        }

        $subscriptions = WeatherSubscription::where('status', true)
            ->where('phone', $r->id)
            ->get();

        if ($subscriptions->isEmpty()) {
            return $this->error('No active subscriptions found for this phone number.');
        }

        $paidSubscriptions = $subscriptions->filter(function ($subscription) {
            return strtoupper($subscription->is_paid) == 'PAID';
        });

        if ($paidSubscriptions->isNotEmpty()) {
            return $this->success($paidSubscriptions, 'Paid subscriptions found!');
        }

        // If there are subscriptions but none are paid, we can check payment status
        foreach ($subscriptions as $subscription) {
            try {
                $subscription->check_payment_status();
            } catch (\Throwable $th) {
                return $this->error($th->getMessage());
            }
        }

        return $this->success($subscriptions, 'Subscriptions found and payment status checked.', 200);
    }
}