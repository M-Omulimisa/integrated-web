<?php

namespace App\Models\Agent;

use App\Models\Settings\Item;
use App\Models\Settings\Parish;
use App\Models\Settings\Seasons;
use App\Models\Payment\MtnApi;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\ItemCategories;

class Subscription extends Model
{
    protected $table = "subscriptions";
    protected $fillable = [
                'session_id',
                'phone',
                'tool',
                'user_type',
                'main_action',
                'name',
                'phonenumber',
                'district',
                'subcounty',
                'parish',
                'season',
                'item_category_id',
                'item_id',
                'item_breed',
                'item_age',
                'item_sex',
                'item_count',
                'sum_per_item',
                'payment_amount',
                'payment_confirmation',
                'payment_refrence',
                'payment_status',
                'status',
                'user_id',
                'sms',
                'agent_code',
                'farm_size',
                'refrence_id',
                'payment_provider',
                'api_id',
                'email_sent'
            ];



    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function item_category()
    {
        return $this->belongsTo(ItemCategories::class, 'item_category_id', 'id');
    }

    public function parish()
    {
        return $this->belongsTo(Parish::class, 'areacode', 'id');
    }

    public function seasons()
    {
        return $this->belongsTo(Seasons::class, 'season', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'user_type', 'id');
    }

    public function payapi()
    {
        return $this->belongsTo(MtnApi::class, 'api_id', 'id');
    }

}