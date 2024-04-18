<?php

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\Ussd\MenuController;
use App\Http\Controllers\Api\v1\ApiAuthController;
use App\Http\Controllers\Api\v1\ApiShopController;
use App\Http\Controllers\Api\v1\InsuranceAPIController;

use App\Http\Middleware\JwtMiddleware;
use App\Models\Market\MarketPackagePricing;
use App\Models\OnlineCourse;
use App\Models\OnlineCourseAfricaTalkingCall;
use App\Models\OnlineCourseLesson;
use App\Models\OnlineCourseStudent;
use App\Models\User;
use App\Models\Utils;
use Carbon\Carbon;
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

//market-package-pricings
Route::get('/market-package-pricings', function (Request $r) {
    $pricings = [];
    foreach (MarketPackagePricing::where([
        'package_id' => $r->q
    ])->get() as $key => $value) {
        $pricings[] = [
            'id' => $value->id,
            'text' => $value->frequency . " - UGX " . $value->cost . ", (" . $value->frequency . ")"
        ];
    }
    return [
        'data' => $pricings
    ];
});


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

Route::post("upload-file", function (Request $r) {
    //upload file name audio
    $path = Utils::file_upload($r->file('audio'));
    if ($path == '') {
        die("failed");
    }
    $session = OnlineCourseLesson::find($r->id);
    if ($session == null) {
        return response()->json([
            'status' => 'error',
            'message' => 'Session not found'
        ]);
    }
    $original = $session;

    if (strlen($path) > 3) {
        $session->has_answer = 'Yes';
        $session->instructor_audio_question = $path;
        $session->student_listened_to_answer = 'No';
        $session->save();
    }

    if ($original->has_answer != 'Yes') {
        if ($session->has_answer == 'Yes') {
            $student = $session->student;
            if ($student != null) {
                $phone = Utils::prepare_phone_number($student->phone);
                if (Utils::phone_number_is_valid($phone)) {
                    try {
                        Utils::send_sms($phone, 'Your instructor has answered your question. Please call 0323200710 to listen to the answer.');
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }
});


Route::group([
    'prefix' => '/v1'
], function () {

    //get all jea 

    Route::match(['get', 'post'], "dmark-sms-webhook", [ApiShopController::class, "dmark_sms_webhook"]);

    Route::middleware([JwtMiddleware::class])->group(function () {
        /* ==============START OF SHOP API================== */
        Route::get("notification-message", [ApiShopController::class, "get_orders_notification_nessage"]);
        Route::POST("notification-messages-seen", [ApiShopController::class, "set_notification_messages_seen"]);
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
        Route::post("weather-subscriptions-status", [ApiShopController::class, 'weather_subscriptions_status']);
        /* ==============END OF SHOP API================== */

        /*==============START OF Market Information Endpoints==============*/
        Route::get("market-packages", [ApiShopController::class, "market_packages"]);
        Route::get("market-subscriptions", [ApiShopController::class, "market_subscriptions"]);
        Route::get("weather-subscriptions", [ApiShopController::class, "weather_subscriptions"]);
        Route::post("market-packages-subscribe", [ApiShopController::class, "market_packages_subscribe"]);
        Route::post("weather-packages-subscribe", [ApiShopController::class, "weather_packages_subscribe"]);
        Route::get("languages", [ApiShopController::class, "languages"]);
        /*==============END OF Market Information Endpoints==============*/
 
        /*==============START OF Insurance Endpoints==============*/
        Route::get("insurance_regions", [InsuranceAPIController::class, "regions"]);
        Route::post("get_region_supported_crops", [InsuranceAPIController::class, "get_region_supported_crops"]);
        Route::get("get_premium_option_details", [InsuranceAPIController::class, "get_premium_option_details"]);
        Route::get("seasons", [InsuranceAPIController::class, "seasons"]);
        Route::get("premium_options", [InsuranceAPIController::class, "premium_options"]);
        Route::get("get_markup", [InsuranceAPIController::class, "getMarkup"]);
        Route::post("insurance-subscribe", [InsuranceAPIController::class, "submitSubscriptionRequest"]);
        /*==============END OF Insurance Endpoints==============*/

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
        Route::get('parishes-2', [ApiAuthController::class, 'parishes_2']);
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
Route::get('/online-make-reminder-calls', function (Request $r) {

    $force = false;
    if (isset($r->force)) {
        if ($r->force == 'Yes') {
            $force = true;
        }
    }

    if (!$force) {
        $now = date('H:i:s');
        if ($now < '16:00:00' || $now > '16:30:00') {
            return;
        }
    }

    //lessons that were attended today
    $today_lessons = \App\Models\OnlineCourseLesson::where('attended_at', '>=', date('Y-m-d 00:00:00'))
        ->where('attended_at', '<=', date('Y-m-d 23:59:59'))
        ->where('status', 'Attended')
        ->get();

    $done_student_ids = [];
    foreach ($today_lessons as $key => $lesson) {
        $done_student_ids[] = $lesson->student_id;
    }

    //pending students
    $pending_students = \App\Models\OnlineCourseStudent::whereNotIn('id', $done_student_ids)
        ->get();

    $students_to_call = [];
    foreach ($pending_students as $key => $pending_student) {

        //get lesson where reminder is yes and reminder date is today
        $lesson = \App\Models\OnlineCourseLesson::where('student_id', $pending_student->id)
            ->where('has_reminder_call', 'Yes')
            ->where('reminder_date', date('Y-m-d'))
            ->first();
        if ($lesson != null) {
            continue;
        }

        //get any latest pending lesson
        $_lesson = \App\Models\OnlineCourseLesson::where('student_id', $pending_student->id)
            ->where('status', 'Pending')
            ->orderBy('position', 'asc')
            ->first();
        if ($_lesson != null) {

            if ($_lesson->has_reminder_call == 'Yes') {
                continue;
            }

            $reminder_date = $_lesson->reminder_date;
            //check if is today
            if ($reminder_date != null && strlen($reminder_date) > 3) {
                $today = Carbon::now();
                $reminder_date = Carbon::parse($reminder_date);
                if ($today->diffInDays($reminder_date) > 0) {
                    continue;
                }
            }
            $students_to_call[] = $pending_student;
            $_lesson->has_reminder_call = 'Yes';
            $_lesson->reminder_date = date('Y-m-d');
            $_lesson->save();
        }
    }

    $client = new \GuzzleHttp\Client();
    $phones = [];
    foreach ($students_to_call as $key => $value) {
        $phone = Utils::prepare_phone_number($value->phone);
        if (!Utils::phone_number_is_valid($phone)) {
            continue;
        }
        //$phone = '+256783204665'; //for testing
        $phones[] = $phone;
    }

    if (count($phones) < 1) {
        die("No students to call.");
    }
    try {
        $client->request('POST', 'https://voice.africastalking.com/call', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apiKey' => '96813c0c9bba6dc78573be66f4965e634e636bee86ffb23ca6d2bebfd9b177bd',
            ],
            'form_params' => [
                'username' => 'dninsiima',
                'to' => implode(',', $phones),
                'from' => '+256323200710',
                'apiKey' => '96813c0c9bba6dc78573be66f4965e634e636bee86ffb23ca6d2bebfd9b177bd',
            ]
        ]);
        foreach ($phones as $key => $value) {
            echo $value . '<br>';
        }
        die("Success");
    } catch (\Exception $e) {
        die("Failed because " . $e->getMessage());
    }
    die("No students to call");
});
Route::post('/online-course-api', function (Request $r) {

    if ($r->direction == 'Inbound') {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://voice.africastalking.com/call', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apiKey' => '96813c0c9bba6dc78573be66f4965e634e636bee86ffb23ca6d2bebfd9b177bd',
            ],
            'form_params' => [
                'username' => 'dninsiima',
                'to' => $r->callerNumber,
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
    $recordingUrl = null;
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
        $recordingUrl = $_POST['recordingUrl'];
        $session->save();
    }

    if ($session->recordingUrl == null || strlen($session->recordingUrl) < 3) {
        if (isset($r->recordingUrl)) {
            $session->recordingUrl = $session->recordingUrl;
            $recordingUrl = $session->recordingUrl;
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

    if ($previous_digit == 6) {
        $lesson->student_audio_question = $recordingUrl;
        $lesson->save();
        $session->digit = 1; //back to main menu
        $session->save();
        Utils::my_resp('audio', 'Done asking a question', $student = $student);
        return;
    }

    if ($r->callSessionState == 'Completed') {
        $session->isActive = 'No';
        $session->save();
        Utils::my_resp('audio', 'Call Ended', $student = $student);
        $session->error_message = json_encode($_POST);
        return;
    } else {

        $session->save();
    }

    if ($digit == 0 && (!$isNewSession)) {
        $session->digit = 1; //back to main menu
        $session->save();
        Utils::my_resp('audio', 'Call Ended', $student = $student);
    }





    if ($digit == null || strlen($digit) < 1 || $digit == 0) {
        $prefixContent = '';
        if ($student->has_listened_to_intro != 'Yes') {
            $course = $lesson->onlineCourse;
            if ($course != null) {
                $intro_audio = $course->audio_url;
                if ($intro_audio != null && strlen($intro_audio) > 3) {
                    $link = url('storage/' . $intro_audio);
                    $prefixContent = '<Play url="' . $link . '" />';
                    $student->has_listened_to_intro = 'Yes';
                    $student->save();
                }
            }
        }
        if ($lesson->has_answer == 'Yes') {
            if ($lesson->student_listened_to_answer != 'Yes') {
                $link = url('storage/' . $lesson->instructor_audio_question);
                $prefixContent .= '<Play url="' . $link . '" />';
                $lesson->student_listened_to_answer = 'Yes';
                $lesson->save();
            }
        }


        Utils::my_resp_digits('audio', 'Main Menu', $student = $student, $prefixContent = $prefixContent);
        return;
    }


    $topic = \App\Models\OnlineCourseTopic::find($lesson->online_course_topic_id);
    if ($topic == null) {
        Utils::my_resp('text', 'Topic not found.');
    }




    if (
        ($previous_digit == 1 && ($digit == 3))
    ) {
        $session->digit = 6; //back to main menu
        $session->save();
        Utils::question_menu($topic, $student);
    }


    if (
        ($previous_digit == 1 && ($digit == 2))
    ) {
        $session->digit = 5; //answering quiz
        $session->save();

        //check if has already attended lesson
        $prefixContent = null;
        try {
            if ($lesson->status != 'Attended') {
                if ($topic->audio_url != null && strlen($topic->audio_url) > 3) {
                    $link = url('storage/' . $topic->audio_url);
                    $prefixContent = '<Play url="' . $link . '" />';
                }
            }
        } catch (\Exception $e) {
            $prefixContent = null;
        }

        if ($prefixContent != null && strlen($prefixContent) > 3) {
            $lesson->attended_at = date('Y-m-d H:i:s');
            $lesson->status = 'Attended';
            $lesson->save();
        }

        Utils::quizz_menu($topic, $prefixContent = $prefixContent);
    }

    if ($previous_digit == 5 && ($digit == 1 || $digit == 2 || $digit == 3)) {
        $lesson->student_quiz_answer = $digit;
        $session->digit = 1; //back to main menu
        $session->save();
        $lesson->save();

        Utils::my_resp_digits('audio', 'Quiz Answered', $student = $student);
    }





    if ($digit == 1 || $digit == 4) {
        $prefixContent = '';
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
        Utils::lesson_menu('audio', 'Lesson menu', $topic, $student = $student, $prefixContent = $prefixContent);
    }



    Utils::my_resp('audio', 'Invalid entry', $student = $student);
    die();
});
