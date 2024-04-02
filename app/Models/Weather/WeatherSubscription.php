<?php

namespace App\Models\Weather;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\ParishModel;
use App\Models\SubcountyModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherSubscriptionRelationship;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;

class WeatherSubscription extends BaseModel
{
    use Uuid, WeatherSubscriptionRelationship;

    protected $fillable = [
        'language_id',
        // 'location_id',
        'district_id',
        'subcounty_id',
        'parish_id',
        'first_name',
        'last_name',
        'email',
        'frequency',
        'period_paid',
        'start_date',
        'end_date',
        'status',
        'user_id',
        'outbox_generation_status',
        'outbox_reset_status',
        'outbox_last_date',
        'awhere_field_id',
        'seen_by_admin',
        'trial_expiry_sms_sent_at',
        'trial_expiry_sms_failure_reason',
        'renewal_id',
        'organisation_id',
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
        self::creating(function (WeatherSubscription $model) {
            $model->id = $model->generateUuid();
            $parish_id = $model->parish_id;
            $parish = ParishModel::find($parish_id);
            if ($parish != null) {
                $model->subcounty_id = $parish->subcounty_id;
                $model->district_id = $parish->district_id;
            }

            //prepare
            $m = self::prepare($model);
        });

        //updating
        self::updating(function (WeatherSubscription $model) {
            $parish_id = $model->parish_id;
            $parish = ParishModel::find($parish_id);
            if ($parish != null) {
                $model->subcounty_id = $parish->subcounty_id;
                $model->district_id = $parish->district_id;
            }

            //prepare
            $m = self::prepare($model);
        });

        //created
        self::created(function (WeatherSubscription $model) {
            $u = User::find($model->farmer_id);
            if ($u == null) {
                $u = User::find($model->user_id);
            }
            $name = $model->first_name;
            $phone = $model->phone;
            if ($u != null) {
                $name = $u->name;
            }

            //welcome message for subscription to weather
            $msg = "Thank you for subscribing to our weather updates. You will receive weather updates every " . $model->frequency . " days. Thank you for subscribing.";
            try {
                //Utils::send_sms($phone, $msg);
            } catch (\Throwable $th) {
                //throw $th; 
            }
            if ($u != null) {
                try {
                    Utils::sendNotification2([
                        'msg' => $msg,
                        'headings' => 'Weather Subscription',
                        'receiver' => $u->id,
                        'type' => 'text',
                    ]);
                } catch (\Throwable $th) {
                }
            }
        });
    }

    //prepare
    public static function prepare($model)
    {
        //period_paid
        $period_paid = $model->period_paid;
        if ($period_paid == null) {
            $period_paid = 0;
        }
        $model->period_paid = $period_paid;
        $days = 0;
        $frequency = strtolower($model->frequency);
        if ($frequency == 'daily') {
            $days = 1;
        } else if ($frequency == 'weekly') {
            $days = 7;
        } else if ($frequency == 'monthly') {
            $days = 30;
        } else if ($frequency == 'yearly') {
            $days = 365;
        }
        $created_date = null;

        if ($model->created_at == null || strlen($model->created_at) < 3) {
            $model->created_at = Carbon::now();
        } else {
            $created_date = Carbon::parse($model->created_at);
        }
        $created_date = Carbon::parse($created_date);
        $model->start_date = $created_date;

        $model->end_date = $created_date->addDays($days * $period_paid);

        //check if end date is less than current date
        $now = Carbon::now();
        $end_date = Carbon::parse($model->end_date);
        if ($now->gt($end_date)) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }

        //format to date only
        $model->start_date = Carbon::parse($model->start_date)->format('Y-m-d');
        $model->end_date = Carbon::parse($model->end_date)->format('Y-m-d');

        return $model;
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

    public function getStatusAttribute($value)
    {
        $now = Carbon::now();
        $then = Carbon::parse($this->end_date);
        if ($now->gt($then)) {
            if ($value == 1) {
                $this->status = 0;
                $this->save();
            }
            return 0;
        } else {
            if ($value == 0) {
                $this->status = 1;
                $this->save();
            }
        }
        if ($value == 1) {
            return 1;
        }
        return 0;
    }
}
