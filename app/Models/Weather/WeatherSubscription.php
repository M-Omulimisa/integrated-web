<?php

namespace App\Models\Weather;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\ParishModel;
use App\Models\Payments\SubscriptionPayment;
use App\Models\SubcountyModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherSubscriptionRelationship;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

            if ($model->is_paid == null || strlen($model->is_paid) < 3) {
                $model->is_paid = 'PAID';
                $model->status = 1;
            }
            $model = self::prepare($model);
            if ($model->is_paid == null || strlen($model->is_paid) < 3) {
                $model->is_paid = 'PAID';
                $model->status = 1;
            }
            return true;
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
            $mmodel = self::prepare($model);
            return true;
        });

        //created
        self::created(function (WeatherSubscription $model) {
        });
    }

    //prepare
    public static function prepare($model)
    {

        if ($model->is_test == 'Yes') {
            return;
        }

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
        $start_date = null;

        if ($model->created_at == null || strlen($model->created_at) < 3) {
            $model->created_at = Carbon::now();
            $model->start_date = Carbon::now();
        } else {
            $created_date = Carbon::parse($model->created_at);
            $start_date = Carbon::parse($model->created_at);
        }


        $created_date = Carbon::parse($created_date);
        $model->start_date = $start_date;


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
        $model->phone = Utils::prepare_phone_number($model->phone);


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

        /* if (strlen($this->TransactionReference) < 3) {
            return 'NOT PAID';
        } */
        if ($this->is_paid == 'PAID') {
            return 'PAID';
        }

        $resp = null;
        try {
            if ($this->TransactionReference != null && strlen($this->TransactionReference) > 3) {
                $resp = Utils::payment_status_check($this->TransactionReference, $this->payment_reference_id);
            }
        } catch (\Throwable $th) {
        }

        if ($resp != null) {
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
        }

        //if not paid
        if ($this->is_paid != 'PAID') {
            $rec = SubscriptionPayment::where('id', $this->payment_id)->orderBy('created_at', 'desc')->first();
            if ($rec == null) {
                $rec = SubscriptionPayment::where('weather_subscription_id', $this->id)->orderBy('created_at', 'desc')->first();
            }
            if ($rec != null) {
                if ($rec->status == 'SUCCESSFUL') {
                    $this->is_paid = 'PAID';
                } else {
                    $this->is_paid = 'NOT PAID';
                }
                $this->MNOTransactionReferenceId = $rec->reference_id;
                $this->TransactionReference = $rec->reference;
                $this->payment_reference_id = $rec->id;
                $this->payment_id = $rec->id;
                $this->TransactionStatus = $rec->status;
                $this->TransactionAmount = $rec->amount;
                $this->TransactionCurrencyCode = 'UGX';
                $this->TransactionInitiationDate = $rec->created_at;
                $this->TransactionCompletionDate = $rec->updated_at;
                $this->total_price = $rec->amount;
                $has_paid = true;
                $this->save();
            }
        }
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
            if ($this->is_paid != 'PAID') {
                $sql = "UPDATE weather_subscriptions SET status = 0 WHERE id = '$this->id'";
                DB::update($sql);
            }
        }


        if ($value == 1) {
            return 1;
        }
        return 0;
    }

    //name_text 
    public function getNameTextAttribute($value)
    {
        if (strlen($this->first_name) < 1) {
            return $this->phone;
        }
        return $this->first_name . ' ' . $this->last_name;
    }

    public function send_renew_message()
    {
        return;
        if ($this->status != 0) {
        }
        $phone = Utils::prepare_phone_number($this->phone);
        //last subscription
        $last_subscription = WeatherSubscription::where([
            'phone' => $phone,
            'renew_message_sent' => 'Yes'
        ])->orderBy('created_at', 'desc')->first();
        if ($last_subscription != null) {
            if ($last_subscription->renew_message_sent_at != null) {
                $t = null;
                try {
                    $t = Carbon::parse($last_subscription->renew_message_sent_at);
                } catch (\Throwable $th) {
                    $t = null;
                }
                if ($t != null) {
                    $now = Carbon::now();
                    $diff = $now->diffInDays($t);
                    if ($diff < 1) {
                        $this->renew_message_sent = 'Skipped';
                        $this->renew_message_sent_at = $now;
                        $this->renew_message_sent_details = 'Already sent a message to this number: ' . $phone . ' within 24 hours. Ref: ' . $last_subscription->id;
                        $this->save();
                        return;
                    }
                }
            }
        }

        $msg = "Your M-Omulimisa subscription to the weather information has expired. Please renew your subscription to continue receiving market updates. Dial *217*101# to renew. Thank you.";
        try {
            Utils::send_sms($phone, $msg);
            $this->renew_message_sent = 'Yes';
            $this->renew_message_sent_at = Carbon::now();
            $this->renew_message_sent_details = $msg . ', Message sent to ' . $phone;
            $this->save();
        } catch (\Throwable $th) {
            $this->renew_message_sent = 'Failed';
            $this->renew_message_sent_at = Carbon::now();
            $this->renew_message_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
            $this->save();
        }
    }

    public function process_subscription()
    {

        //if not paid, check_payment_status
        if ($this->is_paid != 'PAID') {
            $this->check_payment_status();
        }

        if ($this->is_paid == 'PAID') {
            $now = Carbon::now();
            //end date
            $end_date = Carbon::parse($this->end_date);
            if ($now->gt($end_date)) {
                $this->status = 0;
                $this->save();
            } else {
                $this->status = 1;
                $this->save();
            }
        }

        if ($this->is_paid == 'PAID' && $this->status == 1) {
            if ($this->welcome_msg_sent != 'Yes' && $this->welcome_msg_sent != 'Skipped') {
                $this->welcome_msg_sent = 'Yes';
                $this->welcome_msg_sent_at = Carbon::now();
                $msg = "You have subscribed to M-Omulimisa weather information updates. You will now receive updates everyday. Thank you for subscribing.";
                $this->welcome_msg_sent_details = $msg;


                $phone = Utils::prepare_phone_number($this->phone);
                if (!Utils::phone_number_is_valid($phone)) {
                    $this->welcome_msg_sent = 'Failed';
                    $this->welcome_msg_sent_at = Carbon::now();
                    $this->welcome_msg_sent_details = 'Failed to send message to ' . $phone . ', Because: Invalid phone number';
                    $this->save();
                } else {
                    try {
                        Utils::send_sms($phone, $msg);
                        $this->welcome_msg_sent = 'Yes';
                        $this->welcome_msg_sent_details = $msg . ' - Message sent to ' . $phone;
                        $this->save();

                        $subscription = $this;
                        $data = WeatherOutbox::make_sms($subscription);
                        //check if is array
                        if (

                            is_array($data) &&
                            isset($data['status']) &&
                            $data['status'] == 'success' &&
                            isset($data['message']) &&
                            strlen($data['message']) > 5
                        ) {
                            $sms = $data['message'];
                            try {
                                Utils::send_sms($phone, $sms);
                                $this->welcome_msg_sent_details = $sms . ', ' . $this->welcome_msg_sent_details;
                                $this->save();
                            } catch (\Throwable $th) {
                                $this->welcome_msg_sent = 'Failed';
                                $this->welcome_msg_sent_at = Carbon::now();
                                $this->welcome_msg_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
                                $this->save();
                            }
                        }
                    } catch (\Throwable $th) {
                        $this->welcome_msg_sent = 'Failed';
                        $this->welcome_msg_sent_at = Carbon::now();
                        $this->welcome_msg_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
                        $this->save();
                    }
                }
                $this->save();
            }
        }


        $end_date = Carbon::parse($this->end_date);
        //check for expiry $end_date
        $now = Carbon::now();
        if ($now->gt($end_date)) {
            $this->status = 0;
            $this->save();
        } else {
            $this->status = 1;
            $this->save();
        }
        $phone = Utils::prepare_phone_number($this->phone);
        if ($this->status == 1) {
            $now = Carbon::now();
            if ($now->lt($end_date)) {
                $diff = $now->diffInDays($end_date);
                $diff = abs($diff);
                if ($diff < 10) {
                    if ($this->is_paid == 'PAID') {
                        if ($this->pre_renew_message_sent != 'Yes') {
                            if ($diff < 1) {
                                $diff = 1;
                            }
                            $subcount = SubcountyModel::find($this->subcounty_id);
                            $sub_text = '';
                            if ($subcount != null) {
                                $sub_text = $subcount->name_text;
                            }
                            $msg = "Your M-Omulimisa weather information update  for {$sub_text} will expire in next $diff days, Please renew now to avoid disconnection.";

                            try {
                                Utils::send_sms($phone, $msg);
                                $this->pre_renew_message_sent = 'Yes';
                                $this->pre_renew_message_sent_at = Carbon::now();
                                $this->pre_renew_message_sent_details = $msg . ' - Message sent to ' . $phone;
                                $this->save();
                            } catch (\Throwable $th) {
                                $this->pre_renew_message_sent = 'Failed';
                                $this->pre_renew_message_sent_at = Carbon::now();
                                $this->pre_renew_message_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
                                $this->save();
                            }
                        }
                    }
                }
            }
        }

        //if status is 0, send renewal message
        if ($this->status == 0 && $this->is_paid == 'PAID') {
            //check expiry
            $now = Carbon::now();
            $end_date = Carbon::parse($this->end_date);

            if ($now->gt($end_date)) {
                $diff_in_days = $now->diffInDays($end_date);
                if ($diff_in_days < 2) {
                    if ($this->renew_message_sent_at != null) {
                        $t = null;

                        $subcount = SubcountyModel::find($this->subcounty_id);
                        $sub_text = '';
                        if ($subcount != null) {
                            $sub_text = $subcount->name_text;
                        }

                        $msg = "Your M-Omulimisa weather subscription for $sub_text has expired. Please renew your subscription to continue receiving market updates. Dial *217*101# to renew. Thank you.";
                        try {
                            Utils::send_sms($phone, $msg);
                            $this->renew_message_sent = 'Yes';
                            $this->renew_message_sent_at = Carbon::now();
                            $this->renew_message_sent_details = $msg . ', Message sent to ' . $phone;
                            $this->save();
                        } catch (\Throwable $th) {
                            $this->renew_message_sent = 'Failed';
                            $this->renew_message_sent_at = Carbon::now();
                            $this->renew_message_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
                            $this->save();
                        }
                    }
                }
            }
        }




        return $this;
        //is_test
        die('Processing subscription');
        //sync-data
        $phone = Utils::prepare_phone_number($this->phone);
        //last subscription
        $last_subscription = WeatherSubscription::where([
            'phone' => $phone,
            'is_processed' => 'Yes'
        ])->orderBy('created_at', 'desc')->first();
        if ($last_subscription != null) {
            if ($last_subscription->is_processed_at != null) {
                $t = null;
                try {
                    $t = Carbon::parse($last_subscription->is_processed_at);
                } catch (\Throwable $th) {
                    $t = null;
                }
                if ($t != null) {
                    $now = Carbon::now();
                    $diff = $now->diffInDays($t);
                    if ($diff < 1) {
                        $this->is_processed = 'Skipped';
                        $this->is_processed_at = $now;
                        $this->is_processed_details = 'Already processed this number: ' . $phone . ' within 24 hours. Ref: ' . $last_subscription->id;
                        $this->save();
                        return;
                    }
                }
            }
        }

        $msg = "Thank you for subscribing to M-Omulimisa weather information service. You will be receiving weather updates daily.";
        try {
            Utils::send_sms($phone, $msg);
            $this->is_processed = 'Yes';
            $this->is_processed_at = Carbon::now();
            $this->is_processed_details = 'Message sent to ' . $phone;
            $this->save();
        } catch (\Throwable $th) {
            $this->is_processed = 'Failed';
            $this->is_processed_at = Carbon::now();
            $this->is_processed_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
            $this->save();
        }
    }

    //belongs to parish_id
    public function parish()
    {
        return $this->belongsTo(ParishModel::class, 'parish_id', 'id');
    }
}
