<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Payments\SubscriptionPayment;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketSubscriptionRelationship;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

            if ($model->is_paid == null || strlen($model->is_paid) < 3) {
                $model->is_paid = 'PAID';
                $model->status = 1;
            }
            return self::prepare($model);
        });

        //updating
        self::updating(function (MarketSubscription $model) {
            return self::prepare($model);
        });
    }

    //prepre
    public static  function send_weldome_message($model)
    {
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
    }
    public static function prepare($m)
    {
        if ($m->is_test == 'Yes') {
            return;
        }
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

    //name_text 
    public function getNameTextAttribute($value)
    {
        if (strlen($this->first_name) < 1) {
            return $this->phone;
        }
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusAttribute($value)
    {
        $now = Carbon::now();
        $then = Carbon::parse($this->end_date);
        if (((int)($value)) == 1) {
            if ($now->gt($then)) {
                //self::send_renew_message_static($this);
                $sql = "UPDATE market_subscriptions SET status = 0 WHERE id = '{$this->id}'";
                DB::update($sql);
                return 0;
            }
        }
        if ($value == 1) {
            return 1;
        }
        return 0;
    }




    public function send_renew_message()
    {
        if ($this->end_date == null || strlen($this->end_date) < 4) {
            return;
        }
        $end_date = Carbon::parse($this->end_date);
        if ($this->status == 1) {
            $now = Carbon::now();
            if ($now->lt($end_date)) {
                $diff = $now->diffInDays($end_date);
                $diff = abs($diff);
                if ($diff < 2) {
                    if ($this->is_paid == 'PAID') {
                        if ($this->pre_renew_message_sent != 'Yes') {
                            if ($diff < 1) {
                                $diff = 1;
                            }
                            $msg = "Your M-Omulimisa market information subscription for {$this->package->name} will expire in next $diff days, Please renew now to avoid disconnection.";
                            $phone = Utils::prepare_phone_number($this->phone);
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

        $now = Carbon::now();
        $end_date = Carbon::parse($this->end_date);
        if ($this->is_paid == 'PAID' && $this->renew_message_sent != 'Yes') {
            if ($this->status != 1) {
                $phone = Utils::prepare_phone_number($this->phone);
                $msg = "Your M-Omulimisa market information subscription for {$this->package->name} has expired. Please renew your subscription to continue receiving market updates. Dial *217*101# to renew. Thank you.";
                try {
                    Utils::send_sms($phone, $msg);
                    $this->renew_message_sent = 'Yes';
                    $this->renew_message_sent_at = Carbon::now();
                    $this->renew_message_sent_details = $msg . ' - Message sent to ' . $phone;
                    $this->save();
                } catch (\Throwable $th) {
                    $this->renew_message_sent = 'Failed';
                    $this->renew_message_sent_at = Carbon::now();
                    $this->renew_message_sent_details = 'Failed to send message to ' . $phone . ', Because: ' . $th->getMessage();
                    $this->save();
                }
            }
        }
        $created_date = Carbon::parse($this->created_at);

        //welcome_msg_sent
        if ($now->lt($end_date) && $now->gt($created_date) && $this->is_paid == 'PAID') {

            $diff = $now->diffInDays($created_date);
            $diff = abs($diff);
            if ($diff > 3) {
                $this->welcome_msg_sent = 'Skipped';
                $this->welcome_msg_sent_at = Carbon::now();
                $this->welcome_msg_sent_details = 'Skipped because the subscription is older than 3 days. (Days: ' . $diff . ')';
                $this->save();
            } else {
                if ($this->welcome_msg_sent != 'Yes' && $this->welcome_msg_sent != 'Skipped') {
                    $this->welcome_msg_sent = 'Yes';
                    $this->welcome_msg_sent_at = Carbon::now();
                    $mgs = "You have subscribed to M-Omulimisa market information updates. You will now receive updates twice a week. Thank you for subscribing.";
                    $this->welcome_msg_sent_details = $mgs;
                    try {
                        Utils::send_sms($this->phone, $msg);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    $msg = MarketPackageMessage::where([
                        'package_id' => $this->package_id,
                        'language_id' => $this->language_id,
                    ])
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if ($msg != null) {
                        MarketPackageMessage::prepareMessages($msg);
                        $outbox = MarketOutbox::where([
                            'subscription_id' => $this->id,
                        ])
                            ->orderBy('created_at', 'desc')
                            ->first();
                        if ($outbox != null) {
                            $recipient = Utils::prepare_phone_number($outbox->recipient);
                            if (!Utils::phone_number_is_valid($recipient)) {
                                $outbox->status = 'Failed';
                                $outbox->failure_reason = "Invalid phone number";
                                $outbox->save();
                            } else {
                                $outbox->status = 'Sent';
                                Utils::send_sms($recipient, $outbox->message);
                                //message
                                $this->welcome_msg_sent_details = "MARKET UPDATE: " . $outbox->message . ', WELCOME MESSAGE: ' . $this->welcome_msg_sent_details;
                                $outbox->sent_at = Carbon::now();
                                $outbox->save();
                            }
                        }
                    }
                    $this->save();
                }
            }
        }
    }

    public function process_subscription()
    {
        $has_paid = false;
        if ($this->is_paid != 'PAID') {
            if (strtoupper($this->TransactionStatus) != 'SUCCEEDED') {
                if ($this->MNOTransactionReferenceId != null) {
                    if (strlen($this->MNOTransactionReferenceId) > 3) {
                        $has_paid = true;
                    }
                }
            }
            if (strtoupper($this->TransactionStatus) != 'SUCCEEDED') {
                $rec = SubscriptionPayment::where('id', $this->payment_id)->orderBy('created_at', 'desc')->first();
                if ($rec == null) {
                    $rec = SubscriptionPayment::where('market_subscription_id', $this->id)->orderBy('created_at', 'desc')->first();
                }
                if ($rec == null) {
                    $rec = SubscriptionPayment::where('id', $this->payment_id)->orderBy('created_at', 'desc')->first();
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
                    $this->save();
                    $has_paid = true;
                }
            }
        }
        $this->save();
        return $this->is_paid;
    }
}
