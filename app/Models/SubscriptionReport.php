<?php

namespace App\Models;

use App\Models\Market\MarketOutbox;
use App\Models\Market\MarketSubscription;
use App\Models\Organisations\Organisation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionReport extends Model
{
    use HasFactory;

    //boot and call prepare on creating and updating
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model = self::prepare($model);
        });
        self::updating(function ($model) {
            $model = self::prepare($model);
        });
    }

    //prepare
    public static function prepare($m)
    {
        $org = Organisation::find($m->organization_id);
        if ($org == null) {
            throw new \Exception("Organization not found");
        }

        $start_date = null;
        $end_date = null;

        if ($m->date_type == 'this_week') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
        } else if ($m->date_type == 'previous_week') {
            //seven days before today
            $today = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime($today . ' - 7 days'));
            $end_date = date('Y-m-d', strtotime($today . ' - 1 days'));
        } else if ($m->date_type == 'last_week') {
            $start_date = date('Y-m-d', strtotime('monday last week'));
            $end_date = date('Y-m-d', strtotime('sunday last week'));
        } else if ($m->date_type == 'this_month') {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
        } else if ($m->date_type == 'previous_month') {
            $start_date = date('Y-m-01', strtotime('first day of last month'));
            $end_date = date('Y-m-t', strtotime('last day of last month'));
        } else if ($m->date_type == 'last_month') {
            $start_date = date('Y-m-01', strtotime('first day of last month - 1 month'));
            $end_date = date('Y-m-t', strtotime('last day of last month - 1 month'));
        } else if ($m->date_type == 'this_year') {
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31');
        } else if ($m->date_type == 'previous_year') {
            $start_date = date('Y-01-01', strtotime('first day of last year'));
            $end_date = date('Y-12-31', strtotime('last day of last year'));
        } else if ($m->date_type == 'last_year') {
            $start_date = date('Y-01-01', strtotime('first day of last year - 1 year'));
            $end_date = date('Y-12-31', strtotime('last day of last year - 1 year'));
        } else if ($m->date_type == 'custom') {
            $start_date = $m->start_date;
            $end_date = $m->end_date;
        }
        //validate start date and end date
        if ($start_date == null || $end_date == null) {
            throw new \Exception("Invalid date range");
        }

        //in provided start date and end date, how many subscriptions are there
        $m->markert_subs_count = MarketSubscription::where('organization_id', $org->id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $markert_subs_ids = MarketSubscription::where('organization_id', $org->id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->pluck('id');
        $m->markert_sms_count = MarketOutbox::whereIn('subscription_id', $markert_subs_ids)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->count();
        $m->start_date = $start_date;
        $m->end_date = $end_date;
        $m->is_generated = 'Yes';
        $m->pdf_file = null;
        $m->date_generated = date('Y-m-d H:i:s');
        /* 
        title: Services Subscription Report for the {org->name} from {start_date} to {end_date}.
        */
        $m->title = "Services Subscription Report for the {$org->name} from {$start_date} to {$end_date}.";
        return $m;
    }
}
