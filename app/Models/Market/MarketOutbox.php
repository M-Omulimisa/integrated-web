<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\MarketInfoMessageCampaign;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketOutboxRelationship;
use Carbon\Carbon;

class MarketOutbox extends BaseModel
{
    use Uuid, MarketOutboxRelationship;

    protected $table = 'market_outbox';

    protected $fillable = [
        'subscription_id',
        'farmer_id',
        'recipient',
        'message',
        'status',
        'failure_reason',
        'processsed_at',
        'sent_at',
        'failed_at',
        'statuses',
        'sent_via',
    ];


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (MarketOutbox $model) {
            $sub = MarketSubscription::where([
                'id' => $model->subscription_id
            ])->first();
            if ($sub == null) {
                return false;
            }
            if ($sub->status != 1) {
                return false;
            }

            if ($sub->is_paid != 'PAID') {
                return false;
            }

            if ($sub->end_date == null || strlen($sub->end_date) < 3) {
                return false;
            }

            $now = now();
            $expiry = Carbon::parse($sub->end_date);
            if ($now->gt($expiry)) {
                false;
            }

            $model->id = $model->generateUuid();
        });
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    //belongs to campaign
    public function campaign()
    {
        return $this->belongsTo(MarketInfoMessageCampaign::class, 'market_info_message_campaign_id');
    }
}
