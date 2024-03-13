<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketSubscriptionRelationship;
use App\Models\Utils;

class MarketSubscription extends BaseModel
{
    use Uuid, MarketSubscriptionRelationship;
  
    protected $fillable = [
        'farmer_id',
        'language_id',
        // 'location_id',
        'region_id',
        'first_name',
        'last_name',
        'email',
        'frequency',
        'period_paid',
        'start_date',
        'end_date',
        'status',
        'user_id',
        'outbox_count',
        'outbox_generation_status',
        'outbox_reset_status',
        'outbox_last_date',
        'seen_by_admin',
        'trial_expiry_sms_sent_at',
        'trial_expiry_sms_failure_reason',
        'renewal_id',
        'organisation_id',
        'package_id',
        'payment_id',
        'phone'
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
        self::creating(function (MarketSubscription $model) {
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

       //check payment status
       public function check_payment_status()
       {
           if ($this->TransactionReference == null) {
               return 'NOT PAID';
           }
           if (strlen($this->TransactionReference) < 3) {
               return 'NOT PAID';
           }
           $resp = null;
           try {
               $resp = Utils::payment_status_check($this->TransactionReference, $this->payment_reference_id);
           } catch (\Throwable $th) {
               return 'NOT PAID';
           }
           if ($resp == null) {
               return 'NOT PAID';
           }
           if ($resp->Status == 'OK') {
               if ($resp->TransactionStatus == 'PENDING') {
                   $this->TransactionStatus = 'PENDING';
                   if (isset($resp->Amount) && $resp->Amount != null) {
                       $this->TransactionAmount = $resp->Amount;
                   }
                   if (isset($resp->CurrencyCode) && $resp->CurrencyCode != null) {
                       $this->TransactionCurrencyCode = $resp->CurrencyCode;
                   }
                   if (isset($resp->TransactionInitiationDate) && $resp->TransactionInitiationDate != null) {
                       $this->TransactionInitiationDate = $resp->TransactionInitiationDate;
                   }
                   if (isset($resp->TransactionCompletionDate) && $resp->TransactionCompletionDate != null) {
                       $this->TransactionCompletionDate = $resp->TransactionCompletionDate;
                   }
                   $this->save();
               } else if (
                   $resp->TransactionStatus == 'SUCCEEDED' ||
                   $resp->TransactionStatus == 'SUCCESSFUL'
               ) {
                   $this->TransactionStatus = 'SUCCEEDED';
                   if (isset($resp->Amount) && $resp->Amount != null) {
                       $this->TransactionAmount = $resp->Amount;
                   }
                   if (isset($resp->CurrencyCode) && $resp->CurrencyCode != null) {
                       $this->TransactionCurrencyCode = $resp->CurrencyCode;
                   }
                   if (isset($resp->TransactionInitiationDate) && $resp->TransactionInitiationDate != null) {
                       $this->TransactionInitiationDate = $resp->TransactionInitiationDate;
                   }
                   if (isset($resp->TransactionCompletionDate) && $resp->TransactionCompletionDate != null) {
                       $this->TransactionCompletionDate = $resp->TransactionCompletionDate;
                   }
                   //MNOTransactionReferenceId
                   if (isset($resp->MNOTransactionReferenceId) && $resp->MNOTransactionReferenceId != null) {
                       $this->MNOTransactionReferenceId = $resp->MNOTransactionReferenceId;
                   }
                   $this->is_paid = 'PAID';
                   $this->save();
               }
           }
   
           return 'NOT PAID';
       }

}
