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
use App\Models\Product;
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
        $u = Administrator::find($user_id);

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


        $pro = new Product();
        $pro->name = $r->name;
        $pro->feature_photo = 'no_image.jpg';
        $pro->description = $r->description;
        $pro->price_1 = $r->price_1;
        $pro->price_2 = $r->price_2;
        $pro->local_id = $r->id;
        $pro->summary = $r->data;
        $pro->category = $r->category_id;
        $pro->sub_category = $r->category_id;
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
