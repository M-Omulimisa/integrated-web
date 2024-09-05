<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    //boot
    public static function boot()
    {
        parent::boot();
        //created
        self::created(function ($m) {
            try {
                //send mail to admin
                self::send_mail_to_admin($m);
            } catch (\Throwable $th) {
                //throw $th;
            }
        });

        //updating
        self::updating(function ($m) {
            $m->payment_confirmation = 'NOT PAID';
            if (
                $m->TransactionStatus == 'SUCCEEDED' ||
                $m->TransactionStatus == 'SUCCESSFUL'
            ) {
                if ($m->MNOTransactionReferenceId != null && strlen($m->MNOTransactionReferenceId) > 3) {
                    $m->payment_confirmation = 'PAID';
                }
            }

            //check old order_state if is not the same as the new order_state
            $order_state_1 = $m->getOriginal('order_state');
            $order_state_2 = $m->order_state;
            if ($order_state_1 != $order_state_2) {
                //send notification
                $msg = 'Your order #' . $m->id . ' status has been updated to ' . $order_state_2 . '. Incase you need any assistance or have any queries, call us on 0200904415 or visit m-omulimisa.com';
                //thank you.
                $msg .= ' Thank you for shopping with us.';
                //send notification
                try {
                    $u = User::where('phone', $m->customer_phone_number_1)->first();

                    if ($u && $u->id) {
                        Utils::sendNotification2([
                            'msg' => $msg,
                            'headings' => 'Thank you for shopping with us',
                            'receiver' => $u->id,
                            'type' => 'text',
                        ]);
                    }

                    Utils::send_sms($m->customer_phone_number_1, $msg);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        });

        //deleting
        self::deleting(function ($m) {
            try {
                $items = OrderedItem::where('order', $m->id)->get();
                foreach ($items as $item) {
                    $item->delete();
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    //getter for payment_confirmation
    public function get_payment_confirmation($payment_confirmation)
    {
        if (strlen($payment_confirmation) < 1) {
            return 'NOT PAID';
        }

        return strtoupper($payment_confirmation);
    }

    public function get_items()
    {
        $items = [];
        foreach (
            OrderedItem::where([
                'order' => $this->id
            ])->get() as $_item
        ) {
            $pro = Product::find($_item->product);
            if ($pro == null) {
                continue;
            }
            if ($_item->pro == null) {
                continue;
            }
            $_item->product_name = $_item->pro->name;
            $_item->product_feature_photo = $_item->pro->feature_photo;
            $_item->product_price_1 = $_item->pro->price_1;
            $_item->product_quantity = $_item->qty;
            $_item->product_id = $_item->pro->id;
            $items[] = $_item;
        }
        return $items;
    }

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
                $this->payment_confirmation = 'PAID';
                $this->save();
            }
        }

        return 'NOT PAID';
    }

    //send mail to admin
    public static function send_mail_to_admin($m)
    {
        $mails = ['sales@m-omulimisa.com', 'mubahood360@gmail.com'];

        $data['email'] = $mails;
        $email = $data['email'];

        $url = admin_url('online-courses/');
        $msg = "Dear Admin,<br>";
        $msg .= "A new order has been placed on the website.<br>";
        $msg .= "Order ID: " . $m->id . "<br>";
        $msg .= "Customer: " . $m->customer_name . "<br>";
        $msg .= "Phone: " . $m->customer_phone_number_1 . "<br>";
        $msg .= "Email: " . $m->customer_email . "<br>";
        $msg .= "Amount: " . $m->total_amount . "<br>";
        $msg .= "Payment Status: " . $m->payment_confirmation . "<br>";
        $msg .= "Order State: " . $m->order_state . "<br>";
        $msg .= "Order Date: " . $m->created_at . "<br>";
        $review_link = admin_url('orders/' . $m->id . '/edit');
        $msg .= "<a href='" . $review_link . "'>Review Order</a>";
        $msg .= "<br><br><small>This is an automated message, please do not reply.</small><br>";

        $data['body'] = $msg;
        //$data['view'] = 'mails/mail-1';
        $data['data'] = $data['body'];
        $data['name'] = 'Admin';
        $data['mail'] = $email;
        $data['subject'] = "New Order Notification - #" . $m->id . ' - M-Omulimisa';

        try {
            Utils::mail_sender($data);
        } catch (\Throwable $th) {
            // die("FAILED: " . $th->getMessage());
        }
    }
}
