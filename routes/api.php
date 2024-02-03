<?php

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\Ussd\MenuController;
use App\Http\Controllers\Api\v1\ApiAuthController;
use App\Http\Controllers\Api\v1\ApiShopController;
use App\Http\Middleware\JwtMiddleware;
use App\Models\OnlineCourseAfricaTalkingCall;
use App\Models\OnlineCourseStudent;
use App\Models\User;
use App\Models\Utils;
use Dflydev\DotAccessData\Util;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/user', function (Request $request) {
    return 'Testing';
});
Route::get('/select-distcists', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        $conditions[] = ['name', 'like', '%' . $request->q . '%'];
    }
    $districts = \App\Models\DistrictModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});

Route::get('/ajax-users', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        $conditions[] = ['name', 'like', '%' . $request->q . '%'];
    }
    $districts = \App\Models\User::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name . " - #" . $district->id
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});
Route::get('/select-subcounties', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        if ($request->has('by_id')) {
            $conditions['district_id'] = ((int)($request->q));
        } else {
            $conditions[] = ['name', 'like', '%' . $request->q . '%'];
        }
    }
    $districts = \App\Models\SubcountyModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});
Route::get('/select-parishes', function (Request $request) {
    $conditions = [];
    if ($request->has('q')) {
        if ($request->has('by_id')) {
            $conditions['subcounty_id'] = ((int)($request->q));
        } else {
            $conditions[] = ['name', 'like', '%' . $request->q . '%'];
        }
    }
    $districts = \App\Models\ParishModel::where($conditions)->get();
    $data = [];
    foreach ($districts as $district) {
        $data[] = [
            'id' => $district->id,
            'text' => $district->name
        ];
    }
    return response()->json([
        'data' => $data
    ]);
});

Route::group([
    'prefix' => '/v1'
], function () {

    //get all jea 

    Route::middleware([JwtMiddleware::class])->group(function () {
        /* ==============START OF SHOP API================== */
        Route::get("orders", [ApiShopController::class, "orders_get"]);
        Route::get("vendors", [ApiShopController::class, "vendors_get"]);
        Route::post("become-vendor", [ApiShopController::class, 'become_vendor']);
        Route::get('products', [ApiShopController::class, 'products']);
        Route::POST("product-create", [ApiShopController::class, "product_create"]);
        Route::POST("post-media-upload", [ApiShopController::class, 'upload_media']);
        Route::POST('products-delete', [ApiShopController::class, 'products_delete']);
        Route::POST('chat-send', [ApiShopController::class, 'chat_send']);
        Route::get('chat-heads', [ApiShopController::class, 'chat_heads']);
        Route::get('chat-messages', [ApiShopController::class, 'chat_messages']);
        Route::POST('chat-mark-as-read', [ApiShopController::class, 'chat_mark_as_read']);
        Route::POST('chat-start', [ApiShopController::class, 'chat_start']);
        Route::post("orders", [ApiShopController::class, "orders_submit"]);
        Route::POST("orders-delete", [ApiShopController::class, "orders_delete"]);
        Route::post("become-vendor", [ApiShopController::class, 'become_vendor']);
        Route::post("initiate-payment", [ApiShopController::class, 'initiate_payment']);
        Route::post("order-payment-status", [ApiShopController::class, 'order_payment_status']);
        Route::post("market-subscriptions-status", [ApiShopController::class, 'market_subscriptions_status']);
        /* ==============END OF SHOP API================== */

        /*==============START OF Market Information Endpoints==============*/
        Route::get("market-packages", [ApiShopController::class, "market_packages"]);
        Route::get("market-subscriptions", [ApiShopController::class, "market_subscriptions"]);
        Route::post("market-packages-subscribe", [ApiShopController::class, "market_packages_subscribe"]);
        Route::get("languages", [ApiShopController::class, "languages"]);
        /*==============END OF Market Information Endpoints==============*/

        // Authentication
        Route::post("request-otp-sms", [ApiAuthController::class, "request_otp_sms"]);
        Route::POST('login', [ApiAuthController::class, 'login']);
        Route::POST('register', [ApiAuthController::class, 'register']);
        Route::get('me', [ApiAuthController::class, 'me']);
        Route::get("users/me", [ApiAuthController::class, "me"]);
        Route::get('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_requests']);
        Route::get('my-roles', [ApiAuthController::class, 'my_roles']);
        Route::get('resources', [ApiAuthController::class, 'resources']);
        Route::get('resources-categories', [ApiAuthController::class, 'resources_categpries']);
        Route::POST('organisation-joining-requests', [ApiAuthController::class, 'organisation_joining_request_post']);
        Route::get('organisations', [ApiAuthController::class, 'organisations']);
        Route::POST('update-profile', [ApiAuthController::class, 'update_profile']);
        Route::get('farmer-groups', [ApiAuthController::class, 'farmer_groups']);
        Route::get('farmers', [ApiAuthController::class, 'farmers']);
        Route::POST('farmers', [ApiAuthController::class, 'farmers_create']);
        Route::get('countries', [ApiAuthController::class, 'countries']);
        Route::get('locations', [ApiAuthController::class, 'locations']);
        Route::get('languages', [ApiAuthController::class, 'languages']);
        Route::get('trainings', [ApiAuthController::class, 'trainings']);
        Route::get('farmer-questions', [ApiAuthController::class, 'farmer_questions']);
        Route::get('farmer_question_answers', [ApiAuthController::class, 'farmer_question_answers']);
        Route::get('training-sessions', [ApiAuthController::class, 'training_sessions']);
        Route::POST('training-sessions', [ApiAuthController::class, 'training_session_post']);
        Route::POST('gardens-create', [ApiAuthController::class, 'gardens_create']);
        Route::POST('farmer-questions-create', [ApiAuthController::class, 'farmer_questions_create']);
        Route::POST('farmer-answers-create', [ApiAuthController::class, 'farmer_answers_create']);

        Route::get('crops', [ApiAuthController::class, 'crops']);
        Route::get('gardens', [ApiAuthController::class, 'gardens']);
        Route::get('districts', [ApiAuthController::class, 'districts']);
        Route::get('resource-categories', [ApiAuthController::class, 'resource_categories']);
        Route::get('counties', [ApiAuthController::class, 'counties']);
        Route::get('regions', [ApiAuthController::class, 'regions']);
        Route::get('subcounties', [ApiAuthController::class, 'subcounties']);
        Route::get('parishes', [ApiAuthController::class, 'parishes']);
        Route::get('villages', [ApiAuthController::class, 'villages']);
        Route::get('permissions', [ApiAuthController::class, 'permissions']);
        Route::get('my-permissions', [ApiAuthController::class, 'my_permissions']);
        Route::get('roles', [ApiAuthController::class, 'roles']);
    });



    Route::middleware('client_credentials')->group(function () {
        Route::POST('logout', function () {
            Route::POST('logout', [AuthApiController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('api/{model}', [ApiShopController::class, 'update']);
    Route::get('api/{model}', [ApiShopController::class, 'index']);
});

Route::group([
    'namespace' => 'Api\v1\Ussd',
    'prefix' => '/v1'
], function () {

    Route::get('ussdmenu', [MenuController::class, 'index']);
});

Route::group([
    'namespace' => 'Api\v1\Mobile',
    'prefix' => '/v1'
], function () {

    Route::middleware('client_credentials', 'passport.client.set')->group(function () {

        // Route::POST('request', [ApiController::class, 'method']);

    });
});
/* 
id	created_at	updated_at	sessionId	type	phoneNumber	status	postData	cost	

*/
Route::post('/online-course-api', function (Request $r) {


    if (!isset($r->sessionId)) {
        Utils::my_resp('text', 'No session id');
        return;
    }
    if (strlen($r->sessionId) < 3) {
        Utils::my_resp('text', 'Session id too short');
        return;
    }
    if (!isset($r->callSessionState)) {
        Utils::my_resp('text', 'No callSessionState');
        return;
    }

    $previous_digit = 1;
    $isNewSession = false;
    $session = OnlineCourseAfricaTalkingCall::where('sessionId', $r->sessionId)->first();
    if ($session == null) {
        $session = new OnlineCourseAfricaTalkingCall();
        $session->digit = 1;
        $isNewSession = true;
    }
    $previous_digit = $session->digit;
    if ($previous_digit == null) {
        $previous_digit = 1;
    }

    if (isset($_POST['recordingUrl'])) {
        $session->recordingUrl = $_POST['recordingUrl'];
        $session->save();
    }

    if ($session->recordingUrl == null || strlen($session->recordingUrl) < 3) {
        if (isset($r->recordingUrl)) {
            $session->recordingUrl = $session->recordingUrl;
            $session->save();
        }
    }

    $session->sessionId = $r->sessionId;
    $session->type = 'OnlineCourse';
    if (isset($r->callSessionState)) {
        $session->callSessionState = $r->callSessionState;
    }
    if (isset($r->direction)) {
        $session->direction = $r->direction;
    }
    if (isset($r->callerCountryCode)) {
        $session->callerCountryCode = $r->callerCountryCode;
    }
    if (isset($r->durationInSeconds)) {
        $session->durationInSeconds = $r->durationInSeconds;
    }
    if (isset($r->amount)) {
        $session->amount = $r->amount;
        $session->cost = $r->amount;
    }
    if (isset($r->callerNumber)) {
        $session->callerNumber = $r->callerNumber;
        $session->phoneNumber = $r->callerNumber;
    }
    if (isset($r->destinationCountryCode)) {
        $session->destinationCountryCode = $r->destinationCountryCode;
    }
    if (isset($r->destinationNumber)) {
        $session->destinationNumber = $r->destinationNumber;
    }
    if (isset($r->callerCarrierName)) {
        $session->callerCarrierName = $r->callerCarrierName;
    }
    if (isset($r->callStartTime)) {
        $session->callStartTime = $r->callStartTime;
    }
    if (isset($r->destinationNumber)) {
        $session->destinationNumber = $r->destinationNumber;
    }
    if (isset($r->isActive)) {
        $session->isActive = $r->isActive;
    }
    if (isset($r->currencyCode)) {
        $session->currencyCode = $r->currencyCode;
    }

    $digit = null;
    if (isset($r->dtmfDigits)) {
        $session->digit = $r->dtmfDigits;
        $digit = $r->dtmfDigits;
    }

    if ($r->callSessionState != 'Completed') {
        $session->postData = json_encode($_POST);
        $session->save();
    }

    try {
        $session->save();
    } catch (\Exception $e) {
        Utils::my_resp('text', 'Failed to save session.');
    }

    //direction
    if ($session->direction == 'Inbound') {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://voice.africastalking.com/call', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apiKey' => '96813c0c9bba6dc78573be66f4965e634e636bee86ffb23ca6d2bebfd9b177bd',
            ],
            'form_params' => [
                'username' => 'dninsiima',
                'to' => $session->callerNumber,
                'from' => '+256323200710',
                'apiKey' => '96813c0c9bba6dc78573be66f4965e634e636bee86ffb23ca6d2bebfd9b177bd',
            ]
        ]);
        header('Content-type: text/plain');
        echo '<Response> 
                <Reject/>
            </Response>';
        die();
        return;
    }



    /*     if ($session->Answered != 'Answered') {
        return;
    } */


    $phone = Utils::prepare_phone_number($session->callerNumber);
    $user = OnlineCourseStudent::where(['phone' => $phone])->first();
    $student = $user;

    if ($user == null) {
        $session->postData = json_encode($r->all());
        $session->has_error = 'Yes';
        $session->error_message = 'No user with phone number ' . $phone . ' found (' . $session->callerNumber . ')';
        $session->save();
        $number = '0701035192';
        try {
            Utils::send_sms($session->callerNumber, 'Your are not enrolled to any course yet. Please contact M-Omulimisa on ' . $number . ' to get yourself enrolled to online farm courses today. Thank you.');
        } catch (\Exception $e) {
        }
        Utils::my_resp('audio', 'Number not enrolled');
        return;
    }


    $lesson = null;

    $done_lesson = \App\Models\OnlineCourseLesson::where('student_id', $student->id)
        ->where('status', 'Attended')
        ->first();
    if ($done_lesson != null) {
        //check if attended_at is today
        if (date('Y-m-d', strtotime($done_lesson->attended_at)) == date('Y-m-d')) {
            $lesson = $done_lesson;
        } else {
            $lesson = null;
        }
    }

    if ($lesson == null) {
        //get any latest pending lesson
        $_lesson = \App\Models\OnlineCourseLesson::where('student_id', $student->id)
            ->where('status', 'Pending')
            ->orderBy('position', 'asc')
            ->first();
        if ($_lesson != null) {
            $lesson = $_lesson;
        } else {
            $lesson = null;
        }
    }


    if ($lesson == null) {
        Utils::my_resp('text', 'You have no pending lesson for today. Please call tomorrow to listen to your next lesson');
        return;
    }

    if ($digit == 0 && (!$isNewSession)) {
        Utils::my_resp('text', 'Call completed.');
        return;
    }


    if ($digit == null || strlen($digit) < 1 || $digit == 0) {
        Utils::my_resp_digits('audio', 'Main Menu');
        return;
    }


    $topic = \App\Models\OnlineCourseTopic::find($lesson->online_course_topic_id);
    if ($topic == null) {
        Utils::my_resp('text', 'Topic not found.');
    }

    if (
        isset(
            $_POST['recordingUrl'],
        )
    ) {
        $lesson->student_audio_question = $_POST['recordingUrl'];
        $session->digit = 1; //back to main menu
        $session->save();
        $lesson->save();
        echo '<Response>
        <GetDigits timeout="40" numDigits="1" >
          <Play url="https://unified.m-omulimisa.com/storage/files/ttsMP3.com_VoiceText_2024-2-2_22-49-44.mp3" />
        </GetDigits>
        <Say>We did not get your input number. Good bye</Say>
      </Response>';
        Utils::my_resp('text', 'Thank you for asking a question. We will get back to you soon.');
    }


    if (
        ($previous_digit == 1 && ($digit == 3))
    ) {
        $session->digit = 1; //back to main menu
        $session->save();
        Utils::question_menu($topic);
    }


    if (
        ($previous_digit == 1 && ($digit == 2))
    ) {
        $session->digit = 5; //answering quiz
        $session->save();
        Utils::quizz_menu($topic);
    }

    if ($previous_digit == 5 && ($digit == 1 || $digit == 2)) {
        $lesson->student_quiz_answer = $digit;
        $session->digit = 1; //back to main menu
        $session->save();
        $lesson->save();
        Utils::my_resp_digits('audio', 'Quiz Answered');
    }





    if ($digit == 1 || $digit == 4) {
        try {
            if ($lesson->attended_at == null || $lesson->attended_at == '') {
                $lesson->attended_at = date('Y-m-d H:i:s');
            }
            $lesson->status = 'Attended';
            $lesson->save();
        } catch (\Exception $e) {
        }
        $session->digit = 1;
        $session->save();
        Utils::lesson_menu('audio', 'Lesson menu', $topic);
    }

    if ($r->callSessionState == 'Completed') {
        $session->isActive = 'No';
        $session->save();
        Utils::my_resp('text', 'Call completed.');
        $session->error_message = json_encode($_POST);
        return;
    } else {

        $session->save();
    }

    Utils::my_resp('text', 'Invalid option. Please try again.');
    die();
});
