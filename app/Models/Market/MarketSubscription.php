<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketSubscriptionRelationship;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;

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
            $model->created_at = date('Y-m-d H:i:s'); 
            return self::prepare($model);
        });

        //updating
        self::updating(function (MarketSubscription $model) {
            return self::prepare($model);
        });

        //created
        self::created(function (MarketSubscription $model) {
            $u = User::find($model->farmer_id);
            if ($u == null) {
                $u = User::find($model->user_id);
            }
            $name = $model->first_name;
            $phone = $model->phone;
            if ($u != null) {
                $name = $u->name;
            }

            //welcome message for subscription to market
            $msg = "You have successfully subscribed to the market. You will now receive market updates. Thank you for subscribing.";
            try {
                Utils::send_sms($phone, $msg);
            } catch (\Throwable $th) {
                //throw $th;
            }
            if ($u != null) {
                try {
                    Utils::sendNotification2([
                        'msg' => $msg,
                        'headings' => 'Market Subscription',
                        'receiver' => $u->id,
                        'type' => 'text',
                    ]);
                } catch (\Throwable $th) {
                }
            }
        });
    }

    //prepre
    public static function prepare($m)
    {
        $frequencies =  ['trial' => 'trial', 'daily' => 'daily', 'weekly' => 'weekly', 'monthly' => 'monthly', 'yearly' => 'yearly'];
        $frequency_text = "";
        $frequency = null;
        $m->frequency = strtolower($m->frequency);

        $famer = User::find($m->farmer_id);
        if ($famer != null) {
            $famer = User::find($m->user_id);
        }
        if ($famer != null) {
            $m->user_id = $famer->id;
            $m->farmer_id = $famer->id;
        }

        foreach ($frequencies as $key => $value) {
            if ($m->frequency == strtolower($key)) {
                $frequency_text = $value;
                break;
            }
        }
        if ($frequency_text == "") {
            $frequency = MarketPackagePricing::find($m->frequency);
        }
        if ($frequency == null) {
            if (strlen($frequency_text) > 2) {
                $frequency = MarketPackagePricing::where([
                    'package_id' => $m->package_id,
                    'frequency' => $frequency_text
                ])->first();
            }
        }
        if ($frequency == null) {
            $frequency = MarketPackagePricing::where([
                'package_id' => $m->package_id,
                'frequency' => 'trial'
            ])->first();
        }

        $m->period_paid = (int)($m->period_paid);


        $days = 1;
        if (
            strtolower($m->frequency) == 'tiral'
        ) {
            $days = 3 * $m->period_paid;
        } else if (
            strtolower($m->frequency) == 'weekly'
        ) {
            $days = 7 * $m->period_paid;
        } else if (
            strtolower($m->frequency) == 'monthly'
        ) {
            $days = 30 * $m->period_paid;
        } else if (
            strtolower($m->frequency) == 'yearly'
        ) {
            $days = 365 * $m->period_paid;
        }

        $created_time = Carbon::parse($m->created_at);
        $created_time_1 = Carbon::parse($m->created_at);

        $m->start_date = $created_time;
        $m->end_date = $created_time_1->addDays($days);
        $now = Carbon::now();
        if ($now->gt($m->end_date)) {
            $m->status = 0;
        } else {
            $m->status = 1;
        }

        return $m;
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

    //belongs to package_id
    public function package()
    {
        return $this->belongsTo(MarketPackage::class, 'package_id');
    }
}
