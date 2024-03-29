<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\MarketInfoMessageCampaign;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketOutboxRelationship;
  
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
