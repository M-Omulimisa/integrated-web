<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\Animal;
use App\Models\BatchSession;
use App\Models\ChatHead;
use App\Models\ChatMessage;
use App\Models\DistrictModel;
use App\Models\DrugStockBatch;
use App\Models\Event;
use App\Models\Farm;
use App\Models\Image;
use App\Models\Market\MarketPackagePricing;
use App\Models\Market\MarketSubscription;
use App\Models\Movement;
use App\Models\NotificationMessage;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\ParishModel;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SlaughterHouse;
use App\Models\SlaughterRecord;
use App\Models\SubcountyModel;
use App\Models\User;
use App\Models\Utils;
use App\Models\Weather\WeatherSubscription;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Encore\Admin\Auth\Database\Administrator;
use Exception;
use Illuminate\Http\Request;

class ApiShopController extends Controller
{
    use ApiResponser;

    public function languages()
    {
        $items = \App\Models\Settings\Language::where([])->get();
        return $this->success($items, 'Success');
    }

    public function market_packages_subscribe(Request $r)
    {
        /* 
             'subscriber_id': mainController.loggedInUser.id.toString(),
          'package_id': widget.package.id,
          'pricing_id': '27ab2deb-2a6b-445c-bb40-53873aa5bee5',
          'language_id': '0332b59f-9699-4a32-99c4-1dae3666fc93',
        */
        if (!isset($r->subscriber_id) || $r->subscriber_id == null) {
            return $this->error('Subscriber ID is missing.');
        }
        if (!isset($r->package_id) || $r->package_id == null) {
            return $this->error('Package ID is missing.');
        }
        if (!isset($r->pricing_id) || $r->pricing_id == null) {
            return $this->error('Pricing ID is missing.');
        }
        if (!isset($r->language_id) || $r->language_id == null) {
            return $this->error('Language ID is missing.');
        }

        if (!isset($r->total_price) || $r->total_price == null) {
            return $this->error('Total price is missing.');
        }

        //period
        if (!isset($r->period) || $r->period == null) {
            return $this->error('Period is missing.');
        }

        $u = User::find($r->subscriber_id);
        if ($u == null) {
            return $this->error('User not found.');
        }

        $package = \App\Models\Market\MarketPackage::find($r->package_id);
        if ($package == null) {
            return $this->error('Package not found.');
        }

        $pricing = \App\Models\Market\MarketPackagePricing::find($r->pricing_id);
        if ($pricing == null) {
            return $this->error('Pricing not found.');
        }


        $existing_subs = \App\Models\Market\MarketSubscription::where([
            'farmer_id' => $r->subscriber_id,
            'package_id' => $r->package_id,
            'language_id' => $r->language_id,
        ])->get();


        $sub = null;
        foreach ($existing_subs as $key => $val) {
            if ($val->start_date == null || $val->end_date == null) {
                if ($val->status != 1) {
                    $sub = $val;
                    break;
                }
            }

            if (strlen($val->start_date) < 2 || strlen($val->end_date) < 2) {
                if ($val->status != 1) {
                    $sub = $val;
                    break;
                }
            }
            $start_date = Carbon::parse($val->start_date);
            $end_date = Carbon::parse($val->end_date);
            $now = Carbon::now();
            if ($now->between($start_date, $end_date)) {
                if ($val->status != 1) {
                    $sub = $val;
                    break;
                } else {
                    if ($val->is_paid == 'PAID') {
                        return $this->error('You are already subscribed to this package.');
                    }
                    return $this->success($val, 'You are already subscribed to this package.');
                }
            }
        }

        if ($sub != null) {
            $sub->status = 1;
            $sub->save();
            return $this->success($sub, 'Success.');
        }
        $language = \App\Models\Settings\Language::find($r->language_id);
        if ($language == null) {
            return $this->error('Language not found.');
        }

        $subscription = new \App\Models\Market\MarketSubscription();
        $subscription->farmer_id = $r->subscriber_id;
        $subscription->package_id = $r->package_id;
        $subscription->language_id = $language->id;
        $subscription->location_id = 1;
        $subscription->region_id = 1;
        $subscription->district_id = 1;
        $subscription->subcounty_id = 1;
        $subscription->parish_id = 1;
        $subscription->first_name = $u->first_name;
        $subscription->last_name = $u->last_name;
        $subscription->email = $u->email;
        $subscription->frequency = $pricing->frequency;
        $subscription->period_paid = $r->period;
        $subscription->total_price = $r->total_price;
        $subscription->start_date = Carbon::now();
        $subscription->end_date = Carbon::now()->addDays($r->period);
        $subscription->status = 0;
        $subscription->user_id = $u->id;
        $subscription->seen_by_admin = 0;
        $subscription->trial_expiry_sms_sent_at = Carbon::now()->addDays(7);
        $subscription->organisation_id = $u->organisation_id;
        $subscription->package_id = $package->id;
        $subscription->phone = $u->phone;
        $subscription->is_paid = 'NOT PAID'; 
        $phone = $u->phone;
        $phone = Utils::prepare_phone_number($phone);
        if (!Utils::phone_number_is_valid($phone)) {
            $phone = $u->phone_number;
            $phone = Utils::prepare_phone_number($phone);
            if (!Utils::phone_number_is_valid($phone)) {
                return $this->error('Invalid phone number. Update your account phone number and try again.');
            }
        }
        $subscription->phone = Utils::prepare_phone_number($phone);
        $subscription->region_id = 1;
        $subscription->payment_id = 1;
        try {
            $subscription->save();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        $sub = \App\Models\Market\MarketSubscription::find($subscription->id);
        if ($sub == null) {
            return $this->error('Failed to save subscription.');
        }
        return $this->success($sub, 'Success');
    }

    public function weather_packages_subscribe(Request $r)
    {
        /* 
             'subscriber_id': mainController.loggedInUser.id.toString(),
          'package_id': widget.package.id,
          'pricing_id': '27ab2deb-2a6b-445c-bb40-53873aa5bee5',
          'language_id': '0332b59f-9699-4a32-99c4-1dae3666fc93',
        */
        if (!isset($r->subscriber_id) || $r->subscriber_id == null) {
            return $this->error('Subscriber ID is missing.');
        }
        if (!isset($r->pricing_id) || $r->pricing_id == null) {
            return $this->error('Pricing ID is missing.');
        }
        if (!isset($r->language_id) || $r->language_id == null) {
            return $this->error('Language ID is missing.');
        }

        if (!isset($r->total_price) || $r->total_price == null) {
            return $this->error('Total price is missing.');
        }

        if (!isset($r->parish_id) || $r->parish_id == null) {
            return $this->error('Parish ID is missing.');
        }

        if (!isset($r->frequency) || $r->frequency == null) {
            return $this->error('Frequency is missing.');
        }
        if (!isset($r->phone) || $r->phone == null) {
            return $this->error('Phone is missing.');
        }
        if (!isset($r->days) || $r->days == null) {
            return $this->error('Days is missing.');
        }


        //period
        if (!isset($r->period) || $r->period == null) {
            return $this->error('Period is missing.');
        }

        $parish = ParishModel::find($r->parish_id);
        if ($parish == null) {
            return $this->error('Parish not found.');
        }

        $subcounty = SubcountyModel::find($parish->subcounty_id);
        if ($subcounty == null) {
            return $this->error('Subcounty not found.');
        }
        $district = DistrictModel::find($subcounty->district_id);
        if ($district == null) {
            return $this->error('District not found.');
        }


        $u = User::find($r->subscriber_id);
        if ($u == null) {
            return $this->error('User not found.');
        }

        $existing_subs = \App\Models\Market\MarketSubscription::where([
            'farmer_id' => $r->subscriber_id,
        ])->get();


        $sub = null;

        $language = \App\Models\Settings\Language::find($r->language_id);
        if ($language == null) {
            return $this->error('Language not found.');
        }

        $subscription = new WeatherSubscription();

        $subscription->farmer_id = $r->subscriber_id;
        $subscription->language_id = $language->id;
        $subscription->location_id = $parish->id;
        $subscription->district_id = $district->id;
        $subscription->subcounty_id = $subcounty->id;
        $subscription->parish_id = $parish->id;
        $subscription->first_name = $u->first_name;
        $subscription->last_name = $u->last_name;
        if ($subscription->first_name == null || strlen($subscription->first_name) < 2) {
            $subscription->first_name = $u->name;
        }
        if ($subscription->last_name == null || strlen($subscription->last_name) < 2) {
            $subscription->last_name = $u->name;
        }
        $subscription->email = $u->email;
        $subscription->frequency = $r->frequency;
        $subscription->period_paid = $r->period;
        $subscription->total_price = $r->total_price;
        $total_price = $r->total_price;
        $subscription->user_id = $u->id;
        $subscription->status = 0;
        $subscription->outbox_reset_status = 0;
        $subscription->is_paid = 'No';

        if ((int)($total_price) < 100) {
            $subscription->status = 1;
            $subscription->outbox_reset_status = 1;
            $subscription->is_paid = 'Yes';
        }

        $days = ((int)($r->days));
        if ($days < 1) {
            return $this->error('Days should be greater than 0.');
        }
        $subscription->start_date = Carbon::now();
        $subscription->end_date = Carbon::now()->addDays($days);
        $subscription->trial_expiry_sms_sent_at = Carbon::now()->addDays(7);

        $phone_number = Utils::prepare_phone_number($r->phone);
        if (!Utils::phone_number_is_valid($phone_number)) {
            return $this->error('Invalid phone number.');
        }

        $subscription->phone = $phone_number;
        try {
            $subscription->save();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
        $subscription = WeatherSubscription::find($subscription->id);
        if ($subscription == null) {
            return $this->error('Failed to save subscription.');
        }

        $subscription->is_paid = 'NOT PAID';

        return $this->success($subscription, 'Weather subscription saved successfully.');
    }




    public function market_packages()
    {
        $packages = [];
        foreach (\App\Models\Market\MarketPackage::where([])->get() as $package) {
            $pricings = MarketPackagePricing::where([
                'package_id' => $package->id
            ])->get();
            $package->pricings = json_encode($pricings);
            $package->other = json_encode($package->ents);
            $packages[] = $package;
        }
        return $this->success($packages, 'Success');
    }

    public function market_subscriptions(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = User::find($administrator_id);
        }
        if ($u == null) {
            return $this->error('User not found.');
        }
        $subs = \App\Models\Market\MarketSubscription::where([
            'farmer_id' => $u->id
        ])->get();
        return $this->success($subs, 'Success');
    }

    public function weather_subscriptions(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = User::find($administrator_id);
        }

        if ($u == null) {
            return $this->error('User not found.');
        }
        $subs = WeatherSubscription::where([
            'farmer_id' => $u->id
        ])->get();
        return $this->success($subs, 'Success');
    }

    public function weather_subscriptions_status(Request $r)
    {

        if (!isset($r->id) || $r->id == null) {
            return $this->error('Item ID is missing.');
        }

        $item = WeatherSubscription::find($r->id);
        if ($item == null) {
            return $this->error('Item not found.');
        }
        if (strtoupper($item->is_paid) == 'PAID') {
            return $this->success($item, 'Already paid!');
        }

        try {
            $item->check_payment_status();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        $order = WeatherSubscription::find($r->id);
        return $this->success($order, $message = "Success", 200);
    }

    public function market_subscriptions_status(Request $r)
    {

        if (!isset($r->id) || $r->id == null) {
            return $this->error('Item ID is missing.');
        }

        $item = \App\Models\Market\MarketSubscription::find($r->id);
        if ($item == null) {
            return $this->error('Item not found.');
        }
        if (strtoupper($item->is_paid) == 'PAID') {
            return $this->success($item, 'Already paid!');
        }

        try {
            $item->check_payment_status();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        $order = MarketSubscription::find($r->id);
        return $this->success($order, $message = "Success", 200);
    }

    public function order_payment_status(Request $r)
    {

        if (!isset($r->id) || $r->id == null) {
            return $this->error('Item ID is missing.');
        }

        $order = Order::find($r->id);
        if ($order == null) {
            return $this->error('Order not found.');
        }
        if (strtoupper($order->payment_confirmation) == 'PAID') {
            return $this->error('This order #' . $order->id . ' is already paid.');
        }

        try {
            $order->check_payment_status();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        $order = Order::find($r->id);

        return $this->success($order, $message = "Success", 200);
    }

    public function initiate_payment(Request $r)
    {

        if (!isset($r->amount) || $r->amount == null) {
            return $this->error('Amount is missing.');
        }
        if (!isset($r->phone_number) || $r->phone_number == null) {
            return $this->error('Phone number is missing.');
        }
        if (!isset($r->item_id) || $r->item_id == null) {
            return $this->error('Item ID is missing.');
        }
        if (!isset($r->type) || $r->type == null) {
            return $this->error('Type is missing.');
        }

        if (
            $r->type != 'ORDER'
        ) {
            if ($r->type != 'MarketSubscription') {
                if ($r->type != 'WeatherSubscriptionModel') {
                    return $this->error('Invalid type.');
                }
            }
        }

        $tyep = $r->type;
        $MarketSubscription = null;
        $payment_reference_id = time() . rand(1000, 9999);
        if ($r->type == 'ORDER') {
            $order = Order::find($r->item_id);
            if ($order == null) {
                return $this->error('Order not found.');
            }
            if (strtoupper($order->payment_confirmation) == 'PAID') {
                return $this->error('This order #' . $order->id . ' is already paid.');
            }
        } else if ($r->type == 'MarketSubscription') {
            $MarketSubscription = \App\Models\Market\MarketSubscription::find($r->item_id);
            if ($MarketSubscription == null) {
                return $this->error('Market subscription not found.');
            }
            if (strtoupper($MarketSubscription->is_paid) == 'PAID') {
                return $this->error('This market subscription #' . $MarketSubscription->id . ' is already paid.');
            }
        } else if ($r->type == 'WeatherSubscriptionModel') {
            $MarketSubscription = WeatherSubscription::find($r->item_id);
            if ($MarketSubscription == null) {
                return $this->error('Market subscription not found.');
            }
            if (strtoupper($MarketSubscription->is_paid) == 'PAID') {
                return $this->error('This weather subscription #' . $MarketSubscription->id . ' is already paid.');
            }
        }

        $amount = (int)(($r->amount));
        if ($amount < 500) {
            return $this->error('Amount should be greater or equal to UGX 500.');
        }
        $phone_number = Utils::prepare_phone_number($r->phone_number);
        if (!Utils::phone_number_is_valid($r->phone_number)) {
            return $this->error('Invalid phone number ' . $r->phone_number . ".");
        }

        $phone_number = $r->phone_number;
        $phone_number = str_replace('+', '', $phone_number);

        $payment_resp = null;
        try {
            $payment_resp = Utils::init_payment($phone_number, $amount, $payment_reference_id);
        } catch (\Throwable $th) {
            $payment_resp = null;
            return $this->error($th->getMessage());
        }

        if ($payment_resp == null) {
            return $this->error('Failed to initiate payment.');
        }


        if (!isset($payment_resp->Status)) {
            return $this->error('Failed to initiate payment.');
        }

        if ($payment_resp->Status != 'OK') {
            //StatusMessage
            if (isset($payment_resp->StatusMessage)) {
                return $this->error($payment_resp->StatusMessage);
            }
            return $this->error('Failed to initiate payment.');
        }

        //TransactionStatus
        if (!isset($payment_resp->TransactionStatus)) {
            return $this->error('Failed to initiate payment because TransactionStatus is missing.');
        }

        //TransactionReference
        if (!isset($payment_resp->TransactionReference)) {
            return $this->error('Failed to initiate payment because TransactionReference is missing.');
        }

        if ($r->type == 'ORDER') {
            $order->TransactionStatus = $payment_resp->TransactionStatus;
            $order->TransactionReference = $payment_resp->TransactionReference;
            $order->payment_reference_id = $payment_reference_id;
            try {
                $order->save();
                try {
                    $order->check_payment_status();
                } catch (\Throwable $th) {
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else 
        if ($r->type == 'MarketSubscription') {
            $MarketSubscription->TransactionStatus = $payment_resp->TransactionStatus;
            $MarketSubscription->TransactionReference = $payment_resp->TransactionReference;
            $MarketSubscription->payment_reference_id = $payment_reference_id;
            try {
                $MarketSubscription->save();
                try {
                    $MarketSubscription->check_payment_status();
                } catch (\Throwable $th) {
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        } else if ($r->type == 'WeatherSubscriptionModel') {
            $MarketSubscription->TransactionStatus = $payment_resp->TransactionStatus;
            $MarketSubscription->TransactionReference = $payment_resp->TransactionReference;
            $MarketSubscription->payment_reference_id = $payment_reference_id;
            try {
                $MarketSubscription->save();
                try {
                    $MarketSubscription->check_payment_status();
                } catch (\Throwable $th) {
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return $this->success($payment_resp, $message = "GOOD TO GO WITH $phone_number", 200);
    }

    public function set_notification_messages_seen(Request $r)
    {
        $not = NotificationMessage::find($r->notification_id);
        if ($not == null) {
            return $this->error('Notification not found.');
        }
        $not->notification_seen = 'Yes';
        $not->notification_seen_time = Carbon::now();
        $not->save();
    }
    public function dmark_sms_webhook(Request $r)
    {

        $record = new \App\Models\DamarkRercord();

        $record->external_ref = json_encode($r->all());
        $record->sender = $r->sender;
        $record->message_body = $r->body;
        $record->get_data = json_encode($_GET);
        $record->post_data = json_encode($_POST);
        $record->is_processed = 'No';
        $record->status = 'Pending';
        $record->error_message = 'Pending';
        $record->type = 'Other';
        $record->farmer_id = '';
        $record->question_id = '';
        $record->save();
        return $this->success(null, $message = "Success", 200);
    }
    public function get_orders_notification_nessage(Request $r)
    {


        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }
        $u = Administrator::find($u->id);
        $data = NotificationMessage::where([
            'user_id' => $u->id
        ])->get();
        return $this->success($data, $message = "Success!", 200);
    }

    public function orders_get(Request $r)
    {


        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }
        $u = Administrator::find($u->id);


        if ($u == null) {
            return $this->error('User not found.');
        }
        $orders = [];
        $conds = [];

        $conds['user'] = $u->id;

        foreach (Order::where($conds)->get() as $order) {
            $items = $order->get_items();
            $order->items = json_encode($items);
            $orders[] = $order;
        }
        return $this->success($orders, $message = "Success!", 200);
    }

    public function vendors_get(Request $r)
    {
        return $this->success(User::where([
            'user_type' => 'Vendor'
        ])->get(), $message = "Success!", 200);
    }

    public function become_vendor(Request $request)
    {
        $u = auth('api')->user();
        if ($u == null) {
            return $this->error('User not found.');
        }

        if (
            $request->first_name == null ||
            strlen($request->first_name) < 2
        ) {
            return $this->error('First name is missing.');
        }
        //validate all
        if (
            $request->last_name == null ||
            strlen($request->last_name) < 2
        ) {
            return $this->error('Last name is missing.');
        }

        //validate all
        if (
            $request->business_name == null ||
            strlen($request->business_name) < 2
        ) {
            return $this->error('Business name is missing.');
        }

        if (
            $request->business_license_number == null ||
            strlen($request->business_license_number) < 2
        ) {
            return $this->error('Business license number is missing.');
        }

        if (
            $request->business_license_issue_authority == null ||
            strlen($request->business_license_issue_authority) < 2
        ) {
            return $this->error('Business license issue authority is missing.');
        }

        if (
            $request->business_license_issue_date == null ||
            strlen($request->business_license_issue_date) < 2
        ) {
            return $this->error('Business license issue date is missing.');
        }

        if (
            $request->business_license_validity == null ||
            strlen($request->business_license_validity) < 2
        ) {
            return $this->error('Business license validity is missing.');
        }

        if (
            $request->business_address == null ||
            strlen($request->business_address) < 2
        ) {
            return $this->error('Business address is missing.');
        }

        if (
            $request->business_phone_number == null ||
            strlen($request->business_phone_number) < 2
        ) {
            return $this->error('Business phone number is missing.');
        }

        if (
            $request->business_whatsapp == null ||
            strlen($request->business_whatsapp) < 2
        ) {
            return $this->error('Business whatsapp is missing.');
        }

        if (
            $request->business_email == null ||
            strlen($request->business_email) < 2
        ) {
            return $this->error('Business email is missing.');
        }

        $msg = "";
        $u->first_name = $request->first_name;
        $u->last_name = $request->last_name;
        $u->nin = $request->nin;
        $u->business_name = $request->business_name;
        $u->business_license_number = $request->business_license_number;
        $u->business_license_issue_authority = $request->business_license_issue_authority;
        $u->business_license_issue_date = $request->business_license_issue_date;
        $u->business_license_validity = $request->business_license_validity;
        $u->business_address = $request->business_address;
        $u->business_phone_number = $request->business_phone_number;
        $u->business_whatsapp = $request->business_whatsapp;
        $u->business_email = $request->business_email;
        $u->business_cover_photo = $request->business_cover_photo;
        $u->business_cover_details = $request->business_cover_details;


        if ($u->status != 'Active') {
            $u->status = 'Pending';
        }

        $images = [];
        if (!empty($_FILES)) {
            $images = Utils::upload_images_2($_FILES, false);
        }
        if (!empty($images)) {
            $u->business_logo = 'images/' . $images[0];
        }

        $code = 1;
        try {
            $u->save();
            $msg = "Submitted successfully.";
            return $this->success($u, $msg, $code);
        } catch (\Throwable $th) {
            $msg = $th->getMessage();
            $code = 0;
            return $this->error($msg);
        }
        return $this->success(null, $msg, $code);
    }


    public function index(Request $r, $model)
    {

        $className = "App\Models\\" . $model;
        $obj = new $className;

        if (isset($_POST['_method'])) {
            unset($_POST['_method']);
        }
        if (isset($_GET['_method'])) {
            unset($_GET['_method']);
        }

        $conditions = [];
        foreach ($_GET as $k => $v) {
            if (substr($k, 0, 2) == 'q_') {
                $conditions[substr($k, 2, strlen($k))] = trim($v);
            }
        }
        $is_private = true;
        if (isset($_GET['is_not_private'])) {
            $is_not_private = ((int)($_GET['is_not_private']));
            if ($is_not_private == 1) {
                $is_private = false;
            }
        }
        if ($is_private) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);

            if ($u == null) {
                return Utils::response([
                    'status' => 0,
                    'code' => 0,
                    'message' => "User not found.",
                ]);
            }
            $conditions['administrator_id'] = $administrator_id;
        }

        $items = [];
        $msg = "";

        try {
            $items = $className::where($conditions)->get();
            $msg = "Success";
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $msg = $e->getMessage();
        }

        if ($success) {
            return Utils::response([
                'status' => 1,
                'code' => 1,
                'data' => $items,
                'message' => 'Success'
            ]);
        } else {
            return Utils::response([
                'status' => 0,
                'code' => 0,
                'data' => null,
                'message' => $msg
            ]);
        }
    }




    public function chat_messages(Request $r)
    {
        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }
        if ($u == null) {
            return $this->error('User not found.');
        }

        if (isset($r->chat_head_id) && $r->chat_head_id != null) {
            $messages = ChatMessage::where([
                'chat_head_id' => $r->chat_head_id
            ])->get();
            return $this->success($messages, 'Success');
        }
        $messages = ChatMessage::where([
            'sender_id' => $u->id
        ])->orWhere([
            'receiver_id' => $u->id
        ])->get();
        return $this->success($messages, 'Success');
    }



    public function chat_heads(Request $r)
    {
        $u = null;
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }

        if ($u == null) {
            $u = auth('api')->user();
            if ($u == null) {
                $administrator_id = Utils::get_user_id($r);
                $u = Administrator::find($administrator_id);
            }
        }
        if ($u == null) {
            return $this->error('User not found.');
        }
        $chat_heads = ChatHead::where([
            'product_owner_id' => $u->id
        ])->orWhere([
            'customer_id' => $u->id
        ])->get();
        $chat_heads->append('customer_unread_messages_count');
        $chat_heads->append('product_owner_unread_messages_count');
        return $this->success($chat_heads, 'Success');
    }


    public function orders_delete(Request $r)
    {
        if (!isset($r->order_id) || $r->order_id == null) {
            return $this->error('Item ID is missing.');
        }
        $order = Order::find($r->order_id);
        if ($order->payment_confirmation == 'PAID') {
            return $this->error('You cannot delete a paid order.');
        }
        if ($order == null) {
            return $this->error('Order not found.');
        }
        $order->delete();
        return $this->success(null, $message = "Order Deleted Successfully.", 200);
    }

    public function orders_submit(Request $r)
    {

        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }

        $items = [];
        try {
            $items = json_decode($r->items);
        } catch (\Throwable $th) {
            $items = [];
        }

        foreach ($items as $key => $value) {
            $p = Product::find($value->product_id);
            if ($p == null) {
                return $this->error("Product #" . $value->product_id . " not found.");
            }
        }

        if (!is_array($items)) {
            return $this->error("Items are missing.");
        }

        if (count($items) < 1) {
            return $this->error("Items are missing.");
        }

        if ($u == null) {
            return $this->error('User not found.');
        }

        $delivery = null;
        try {
            $delivery = json_decode($r->delivery);
        } catch (\Throwable $th) {
            $delivery = null;
        }

        if ($delivery == null) {
            return $this->error('Delivery information is missing.');
        }
        if ($delivery->phone_number == null) {
            return $this->error('Phone number is missing.');
        }

        $order = new Order();
        $order->user = $u->id;
        $order->order_state = 0;
        $order->temporary_id = 0;
        $order->amount = 0;
        $order->order_total = 0;
        $order->payment_confirmation = '';
        $order->description = '';
        $order->mail = $delivery->email;
        $order->date_created = Carbon::now();
        $order->date_updated = Carbon::now();
        $order->save();
        $order_total = 0;
        foreach ($items as $key => $item) {
            $product = Product::find($item->product_id);
            if ($product == null) {
                return $this->error("Product #" . $item->product_id . " not found.");
            }
            $oi = new OrderedItem();
            $oi->order = $order->id;
            $oi->product = $item->product_id;
            $oi->qty = $item->product_quantity;
            $oi->amount = $product->price_1;
            $oi->color = '';
            $oi->size = '';
            $order_total += ($product->price_1 * $oi->qty);
            $oi->save();
        }
        $order->order_total = $order_total;
        $order->amount = $order_total;
        $order->customer_phone_number_1 = $delivery->phone_number;
        $order->payment_confirmation = 'Not Paid';
        $order->order_state = 'Pending'; // 'Pending', 'Processing', 'Completed', 'Cancelled
        $order->description = $delivery->address;
        $order->save();

        //send notification to customer, how order was received
        $noti_title = "Order Received";
        $noti_body = "Your order has been received. We will contact you soon. Thank you.";
        try {
            Utils::sendNotification(
                $noti_body,
                $u->id,
                $noti_title,
                data: [
                    'id' => $order->id,
                    'user' => $u->id,
                    'order_state' => $order->order_state,
                    'amount' => $order->amount,
                    'order_total' => $order->order_total,
                    'payment_confirmation' => $order->payment_confirmation,
                    'description' => $order->description,
                    'customer_phone_number_1' => $order->customer_phone_number_1,
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
        if ($order == null) {
            return $this->error('Failed to save order.');
        }
        //Utils::send_sms($noti_body, $delivery->phone_number);
        $order = Order::find($order->id);

        $_items = $order->get_items();
        $order->items = json_encode($_items);

        return $this->success($order, $message = "Order Submitted Successfully.", 200);
    }



    public function chat_start(Request $r)
    {
        $sender = null;
        if ($sender == null) {
            $administrator_id = Utils::get_user_id($r);
            $sender = Administrator::find($administrator_id);
        }
        if ($sender == null) {
            return $this->error('User not found.');
        }
        $receiver = User::find($r->receiver_id);
        if ($receiver == null) {
            return $this->error('Receiver not found.');
        }
        $pro = Product::find($r->product_id);
        if ($pro == null) {
            return $this->error('Product not found.');
        }
        $product_owner = null;
        $customer = null;

        if ($pro->user == $sender->id) {
            $product_owner = $sender;
            $customer = $receiver;
        } else {
            $product_owner = $receiver;
            $customer = $sender;
        }

        $chat_head = ChatHead::where([
            'product_id' => $pro->id,
            'product_owner_id' => $product_owner->id,
            'customer_id' => $customer->id
        ])->first();
        if ($chat_head == null) {
            $chat_head = ChatHead::where([
                'product_id' => $pro->id,
                'customer_id' => $product_owner->id,
                'product_owner_id' => $customer->id
            ])->first();
        }

        if ($chat_head == null) {
            $chat_head = new ChatHead();
            $chat_head->product_id = $pro->id;
            $chat_head->product_owner_id = $product_owner->id;
            $chat_head->customer_id = $customer->id;
            $chat_head->product_name = $pro->name;
            $chat_head->product_photo = $pro->feature_photo;
            $chat_head->product_owner_name = $product_owner->name;
            $chat_head->product_owner_photo = $product_owner->photo;
            $chat_head->customer_name = $customer->name;
            $chat_head->customer_photo = $customer->photo;
            $chat_head->last_message_body = '';
            $chat_head->last_message_time = Carbon::now();
            $chat_head->last_message_status = 'sent';
            $chat_head->save();
        }

        return $this->success($chat_head, 'Success');
    }



    public function chat_mark_as_read(Request $r)
    {
        $receiver = Administrator::find($r->receiver_id);
        if ($receiver == null) {
            return $this->error('Receiver not found.');
        }
        $chat_head = ChatHead::find($r->chat_head_id);
        if ($chat_head == null) {
            return $this->error('Chat head not found.');
        }
        $messages = ChatMessage::where([
            'chat_head_id' => $chat_head->id,
            'receiver_id' => $receiver->id,
            'status' => 'sent'
        ])->get();
        foreach ($messages as $key => $message) {
            $message->status = 'read';
            $message->save();
        }
        return $this->success($messages, 'Success');
    }

    public function chat_send(Request $r)
    {

        $sender = auth('api')->user();

        $user_id = $r->user;
        if ($sender == null) {
            $sender = Administrator::find($user_id);
        }

        if ($sender == null) {
            $administrator_id = Utils::get_user_id($r);
            $sender = Administrator::find($administrator_id);
        }
        if ($sender == null) {
            return $this->error('User not found.');
        }
        $receiver = User::find($r->receiver_id);
        if ($receiver == null) {
            return $this->error('Receiver not found.');
        }
        $pro = Product::find($r->product_id);
        if ($pro == null) {
            return $this->error('Product not found.');
        }
        $product_owner = null;
        $customer = null;

        if ($pro->user == $sender->id) {
            $product_owner = $sender;
            $customer = $receiver;
        } else {
            $product_owner = $receiver;
            $customer = $sender;
        }

        $chat_head = ChatHead::where([
            'product_id' => $pro->id,
            'product_owner_id' => $product_owner->id,
            'customer_id' => $customer->id
        ])->first();
        if ($chat_head == null) {
            $chat_head = ChatHead::where([
                'product_id' => $pro->id,
                'customer_id' => $product_owner->id,
                'product_owner_id' => $customer->id
            ])->first();
        }

        if ($chat_head == null) {
            $chat_head = new ChatHead();
            $chat_head->product_id = $pro->id;
            $chat_head->product_owner_id = $product_owner->id;
            $chat_head->customer_id = $customer->id;
            $chat_head->product_name = $pro->name;
            $chat_head->product_photo = $pro->feature_photo;
            $chat_head->product_owner_name = $product_owner->name;
            $chat_head->product_owner_photo = $product_owner->photo;
            $chat_head->customer_name = $customer->name;
            $chat_head->customer_photo = $customer->photo;
            $chat_head->last_message_body = $r->body;
            $chat_head->last_message_time = Carbon::now();
            $chat_head->last_message_status = 'sent';
            $chat_head->save();
        }
        $chat_message = new ChatMessage();
        $chat_message->chat_head_id = $chat_head->id;
        $chat_message->sender_id = $sender->id;
        $chat_message->receiver_id = $receiver->id;
        $chat_message->sender_name = $sender->name;
        $chat_message->sender_photo = $sender->photo;
        $chat_message->receiver_name = $receiver->name;
        $chat_message->receiver_photo = $receiver->photo;
        $chat_message->body = $r->body;
        $chat_message->type = 'text';
        $chat_message->status = 'sent';
        $chat_message->save();
        $chat_head->last_message_body = $r->body;
        $chat_head->last_message_time = Carbon::now();
        $chat_head->last_message_status = 'sent';
        $chat_head->save();
        return $this->success($chat_message, 'Success');
    }




    public function products()
    {
        return $this->success(Product::where([])->orderby('id', 'desc')->get(), 'Success');
    }

    public function products_delete(Request $r)
    {
        $pro = Product::find($r->id);
        if ($pro == null) {
            return $this->error('Product not found.');
        }
        try {
            $pro->delete();
            return $this->success(null, $message = "Sussesfully deleted!", 200);
        } catch (\Throwable $th) {
            return $this->error('Failed to delete product.');
        }
    }


    public function product_create(Request $r)
    {

        $user_id = $r->user;
        $u = User::find($user_id);
        if ($u == null) {
            return $this->error('User not found.');
        }

        if (
            !isset($r->id) ||
            $r->name == null ||
            ((int)($r->id)) < 1
        ) {
            return $this->error('Local parent ID is missing.');
        }


        $isEdit = false;
        if (isset($r->is_edit) && $r->is_edit == 'Yes') {
            $pro = Product::find($r->id);
            if ($pro == null) {
                return $this->error('Product not found.');
            }
            $isEdit = true;
        } else {
            $pro = new Product();
        }

        $pro->name = $r->name;
        $pro->feature_photo = 'no_image.jpg';
        $pro->description = $r->description;
        $pro->price_1 = $r->price_1;
        $pro->price_2 = $r->price_2;
        $pro->local_id = $r->id;
        $pro->summary = $r->data;
        $pro->p_type = $r->p_type;
        $pro->keywords = $r->keywords;
        $pro->metric = 1;
        $pro->status = 0;
        $pro->currency = 1;
        $pro->url = $u->url;
        $pro->user = $u->id;
        $pro->supplier = $u->id;
        $pro->in_stock = $r->in_stock;
        $pro->rates = 1;


        $cat = ProductCategory::find($r->category);
        if ($cat == null) {
            return $this->error('Category not found.');
        }
        $pro->category = $cat->id;

        $pro->date_added = Carbon::now();
        $pro->date_updated = Carbon::now();
        $imgs = Image::where([
            'parent_id' => $pro->local_id
        ])->get();
        if ($imgs->count() > 0) {
            $pro->feature_photo = $imgs[0]->src;
        }
        if ($pro->save()) {
            foreach ($imgs as $key => $img) {
                $img->product_id = $pro->id;
                $img->save();
            }
            if ($isEdit) {
                return $this->success(null, $message = "Updated successfully!", 200);
            }
            return $this->success(null, $message = "Submitted successfully!", 200);
        } else {
            return $this->error('Failed to upload product.');
        }
    }

    public function upload_media(Request $request)
    {
        $administrator_id = Utils::get_user_id($request);
        $u = Administrator::find($administrator_id);
        if ($u == null) {
            return Utils::response([
                'status' => 0,
                'code' => 0,
                'message' => "User not found.",
            ]);
        }


        if (
            !isset($request->parent_id) ||
            $request->parent_id == null ||
            ((int)($request->parent_id)) < 1
        ) {

            return Utils::response([
                'status' => 0,
                'code' => 0,
                'message' => "Local parent ID is missing.",
            ]);
        }


        if (
            !isset($request->parent_endpoint) ||
            $request->parent_endpoint == null ||
            (strlen(($request->parent_endpoint))) < 3
        ) {
            return Utils::response([
                'status' => 0,
                'code' => 0,
                'message' => "Local parent ID endpoint is missing.",
            ]);
        }

        if (
            empty($_FILES)
        ) {
            return Utils::response([
                'status' => 0,
                'code' => 0,
                'message' => "Files not found.",
            ]);
        }

        $images = Utils::upload_images_1($_FILES, false);
        $_images = [];

        if (empty($images)) {
            return Utils::response([
                'status' => 0,
                'code' => 0,
                'message' => 'Failed to upload files.',
                'data' => null
            ]);
        }

        $msg = "";
        foreach ($images as $src) {

            if ($request->parent_endpoint == 'edit') {
                $img = Image::find($request->local_parent_id);
                if ($img) {
                    return Utils::response([
                        'status' => 0,
                        'code' => 0,
                        'message' => "Original photo not found",
                    ]);
                }
                $img->src =  $src;
                $img->thumbnail =  null;
                $img->save();
                return Utils::response([
                    'status' => 1,
                    'code' => 1,
                    'data' => json_encode($img),
                    'message' => "File updated.",
                ]);
            }


            $img = new Image();
            $img->administrator_id =  $administrator_id;
            $img->src =  $src;
            $img->thumbnail =  null;
            $img->parent_endpoint =  $request->parent_endpoint;
            $img->parent_id =  (int)($request->parent_id);
            $img->size = 0;
            $img->note = '';
            if (
                isset($request->note)
            ) {
                $img->note =  $request->note;
                $msg .= "Note not set. ";
            }

            $online_parent_id = ((int)($request->online_parent_id));
            if (
                $online_parent_id > 0
            ) {
                $animal = Product::find($online_parent_id);
                if ($animal != null) {
                    $img->parent_endpoint =  'Animal';
                    $img->parent_id =  $animal->id;
                } else {
                    $msg .= "parent_id NOT not found => {$request->online_parent_id}.";
                }
            } else {
                $msg .= "Online_parent_id NOT set. => {$online_parent_id} ";
            }

            $img->save();
            $_images[] = $img;
        }
        //Utils::process_images_in_backround();
        return Utils::response([
            'status' => 1,
            'code' => 1,
            'data' => json_encode($_POST),
            'message' => "File uploaded successfully.",
        ]);
    }
}
