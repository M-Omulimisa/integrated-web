<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\Animal;
use App\Models\BatchSession;
use App\Models\ChatHead;
use App\Models\ChatMessage;
use App\Models\DrugStockBatch;
use App\Models\Event;
use App\Models\Farm;
use App\Models\Image;
use App\Models\Movement;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SlaughterHouse;
use App\Models\SlaughterRecord;
use App\Models\User;
use App\Models\Utils;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Exception;
use Illuminate\Http\Request;

class ApiShopController extends Controller
{

    use ApiResponser;

    public function orders_get(Request $r)
    {

        $u = auth('api')->user();
        if ($u == null) {
            $administrator_id = Utils::get_user_id($r);
            $u = Administrator::find($administrator_id);
        }

        if ($u == null) {
            return $this->error('User not found.');
        }
        $orders = [];
        $conds = [];

        if(!$u->isRole('admin')){
            $conds['user'] = $u->id;
        }

        foreach (Order::where($conds)->get() as $order) {
            $items = $order->get_items();
            $order->items = json_encode($items);
            $orders[] = $order;
        }
        return $this->success($orders, $message = "Success!", 200);
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
        $order->mail = $u->email;
        $order->date_created = Carbon::now();
        $order->date_updated = Carbon::now();
        if ($delivery != null) {
            try {
                $order->customer_phone_number_1 = $delivery->phone_number;
                $order->customer_phone_number_2 = $delivery->phone_number_2;
                $order->customer_name = $delivery->first_name . " " . $delivery->last_name;
                $order->customer_address = $delivery->current_address;
                $order->delivery_district = $delivery->current_address;
                $order->order_details = json_encode($delivery);
            } catch (\Throwable $th) {
            }
        }

        $order->save();


        foreach ($items as $key => $item) {
            $oi = new OrderedItem();
            $oi->order = $order->id;
            $oi->product = $item->product_id;
            $oi->qty = $item->product_quantity;
            $oi->amount = $item->product_price_1;
            $oi->color = '';
            $oi->size = '';
            $oi->save();
        }

        return $this->success(null, $message = "Submitted successfully!", 200);
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
        $pro->in_stock = 1;
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
