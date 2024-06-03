<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\MarketInfoMessageCampaign;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketPackageLanguageRelationship;
use Carbon\Carbon;

class MarketPackageMessage extends BaseModel
{
    use Uuid, MarketPackageLanguageRelationship;

    protected $fillable = [
        'package_id',
        'language_id',
        'menu',
        'message'
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
        self::creating(function (MarketPackageMessage $model) {
            $model->id = $model->generateUuid();
        });
        self::updated(function (MarketPackageMessage $model) {
            self::prepareMessages($model);
        });
        self::created(function (MarketPackageMessage $model) {
            self::prepareMessages($model);
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


    public static function prepareMessages($item)
    {
        $subScribers = MarketSubscription::where([
            'package_id' => $item->package_id,
            'language_id' => $item->language_id,
        ])
            ->get();
        foreach ($subScribers as $sub) {
            if ($sub->status != 1) {
                continue;
            }
            if ($sub->is_paid != 'PAID') {
                continue;
            }

            if ($sub->end_date == null || strlen($sub->end_date) < 3) {
                continue;
            }

            //check expiry 
            $now = now();
            $expiry = Carbon::parse($sub->end_date);
            if ($now->gt($expiry)) {
                continue;
            }

            $outbox = MarketOutbox::where([
                'subscription_id' => $sub->id,
                'market_package_message_id' => $item->id,
            ])->first();
            if ($outbox == null) {
                $outbox = new MarketOutbox();
                $outbox->subscription_id = $sub->id;
                $outbox->market_package_message_id = $item->id;
                $outbox->status = 'Pending';
                $outbox->statuses = 'Pending';
                $outbox->created_at = now();
                $outbox->updated_at = now();
                //$outbox->save();
            }
            $outbox->market_info_message_campaign_id = $item->market_info_message_campaign_id;
            $outbox->farmer_id = $sub->farmer_id;
            $outbox->recipient = $sub->phone;
            $outbox->message = $item->message;
            $outbox->processsed_at = null;
            $outbox->sent_at = null;
            $outbox->failed_at = null;
            $outbox->sent_via = 'SMS';
            $outbox->failure_reason = "";
            $outbox->save();
        };
    }

    //belongs to campaign
    public function campaign()
    {
        return $this->belongsTo(MarketInfoMessageCampaign::class, 'market_info_message_campaign_id');
    }

    //has many outboxes
    public function outboxes()
    {
        return $this->hasMany(MarketOutbox::class, 'market_package_message_id');
    }
}
