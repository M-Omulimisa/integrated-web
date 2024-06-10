<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\OtpController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\PermissionController;
use App\Http\Controllers\Users\SessionLogController;
use App\Http\Controllers\Users\ActivityLogController;

use App\Http\Controllers\Farmers\FarmerController;
use App\Http\Controllers\Farmers\FarmerGroupController;
use App\Http\Controllers\Farmers\FarmerMappingController;

use App\Http\Controllers\Agents\AgentController;
use App\Http\Controllers\Agents\AgentMappingController;

use App\Http\Controllers\Extension\ExtensionOfficerController;
use App\Http\Controllers\Extension\ExtensionOfficerPositionController;
use App\Http\Controllers\Extension\ExtensionOfficerMappingController;

use App\Http\Controllers\Organisations\OrganisationController;
use App\Http\Controllers\Organisations\OrganisationUserController;
use App\Http\Controllers\Organisations\OrganisationPermissionController;
use App\Http\Controllers\Organisations\OrganisationPositionController;

use App\Http\Controllers\Insurance\InsuranceFarmerCompensationController;
use App\Http\Controllers\Insurance\InsuranceCompensationHistoryController;
use App\Http\Controllers\Insurance\InsuranceAgentEarningController;
use App\Http\Controllers\Insurance\InsuranceAgentEarningHistoryController;
use App\Http\Controllers\Insurance\InsuranceSubscriptionController;
use App\Http\Controllers\Insurance\InsuranceTransactionController;
use App\Http\Controllers\Insurance\InsuranceCalculatorController;
use App\Http\Controllers\Insurance\InsuranceLossManagementController;
use App\Http\Controllers\Insurance\InsuranceAgentsController;
use App\Http\Controllers\Insurance\InsuranceCompanyController;
use App\Http\Controllers\Insurance\InsurancePremiumOptionController;
use App\Http\Controllers\Insurance\InsurancePeriodController;
use App\Http\Controllers\Insurance\InsuranceAgentCommisionController;
use App\Http\Controllers\Insurance\InsuranceFullCoverageRatesController;

use App\Http\Controllers\Questions\QuestionController;
use App\Http\Controllers\Questions\QuestionResponseController;
use App\Http\Controllers\Questions\QuestionMappingController;

use App\Http\Controllers\Alerts\AlertController;
use App\Http\Controllers\Alerts\AlertMappingController;
use App\Http\Controllers\Alerts\OutbreakController;

use App\Http\Controllers\Trainings\TrainingResouceController;
use App\Http\Controllers\Trainings\TrainingResouceSectionController;
use App\Http\Controllers\Trainings\TrainingController;
use App\Http\Controllers\Trainings\ResourceTopicController;
use App\Http\Controllers\Trainings\ResourceSubTopicController;

// use App\Http\Controllers\Elearning\CourseController;
// use App\Http\Controllers\Elearning\InstructorController;
// use App\Http\Controllers\Elearning\InstructorInvitationController;
// use App\Http\Controllers\Elearning\StudentController;
// use App\Http\Controllers\Elearning\DefaultInstructionController;
// use App\Http\Controllers\Elearning\DefaultMessageController;
// use App\Http\Controllers\Elearning\CallbackTimeController;

use App\Http\Controllers\Elearning\InstructorController;
use App\Http\Controllers\Elearning\InstructorInvitationController;
use App\Http\Controllers\Elearning\CourseController;
use App\Http\Controllers\Elearning\StudentController;
use App\Http\Controllers\Elearning\ChapterController;
use App\Http\Controllers\Elearning\WeekController;
use App\Http\Controllers\Elearning\LectureController;
use App\Http\Controllers\Elearning\AnnouncementController;
use App\Http\Controllers\Elearning\ResourcesController;
use App\Http\Controllers\Elearning\ForumController;
use App\Http\Controllers\Elearning\CourseStudentsController;
use App\Http\Controllers\Elearning\AssignmentController;
use App\Http\Controllers\Elearning\InstructionsController;
use App\Http\Controllers\Elearning\CourseInstructionController;
use App\Http\Controllers\Elearning\GeneralAssignmentController;
use App\Http\Controllers\Elearning\CourseAnalyticsController;
use App\Http\Controllers\Elearning\SysteOutCallsController;
use App\Http\Controllers\Elearning\MessagesController;
use App\Http\Controllers\Elearning\CourseMessageController;

use App\Http\Controllers\MarketInformation\SubscriptionKeywordController;
use App\Http\Controllers\MarketInformation\OnRequestKeywordController;
use App\Http\Controllers\MarketInformation\CommodityPriceController;
use App\Http\Controllers\MarketInformation\MarketSubscriptionController;
use App\Http\Controllers\MarketInformation\MarketTransactionController;
use App\Http\Controllers\MarketInformation\MarketOutputProductController;
use App\Http\Controllers\MarketInformation\MarketsController;
use App\Http\Controllers\MarketInformation\MarketOutboxController;
use App\Http\Controllers\MarketInformation\MarketPackageController;

use App\Http\Controllers\Weather\WeatherOutboxController;
use App\Http\Controllers\Weather\WeatherSubscriptionController;
use App\Http\Controllers\Weather\WeatherTransactionController;
use App\Http\Controllers\Weather\WeatherConditionController;
use App\Http\Controllers\Weather\WeatherTriggertroller;

use App\Http\Controllers\InputLoan\InputRequestController;
use App\Http\Controllers\InputLoan\PurchaseOrderController;
use App\Http\Controllers\InputLoan\LoanRepaymentController;
use App\Http\Controllers\InputLoan\LoanSettingController;
use App\Http\Controllers\InputLoan\LpoSettingController;
use App\Http\Controllers\InputLoan\LoanChargeController;
use App\Http\Controllers\InputLoan\InputCommissionRateController;
use App\Http\Controllers\InputLoan\MicrofinanceController;
use App\Http\Controllers\InputLoan\ServiceProviderController;
use App\Http\Controllers\InputLoan\BuyerController;
use App\Http\Controllers\InputLoan\DistributorController;
use App\Http\Controllers\InputLoan\InputPriceController;
use App\Http\Controllers\InputLoan\OutputPriceController;
use App\Http\Controllers\InputLoan\InputProjectController;
use App\Http\Controllers\InputLoan\YieldEstimationController;

use App\Http\Controllers\Settings\CountryController;
use App\Http\Controllers\Settings\LocationController;
use App\Http\Controllers\Settings\LanguageController;
use App\Http\Controllers\Settings\EnterpriseController;
use App\Http\Controllers\Settings\ModuleController;
use App\Http\Controllers\Settings\CountryModuleController;
use App\Http\Controllers\Settings\SeasonController;
use App\Http\Controllers\Settings\MarkupController;
use App\Http\Controllers\Settings\RegionController;
use App\Http\Controllers\Settings\CountryUnitController;
use App\Http\Controllers\Settings\KeywordController;
use App\Http\Controllers\Settings\EnterpriseVarityController;
use App\Http\Controllers\Settings\AgroProductController;
use App\Http\Controllers\Settings\KeywordSuccessResponseController;
use App\Http\Controllers\Settings\KeywordFailureResponseController;
use App\Http\Controllers\Settings\CommissionRankingController;
use App\Http\Controllers\Settings\EnterpriseTypeController;
use App\Http\Controllers\Settings\UnitController;
use App\Http\Controllers\Settings\CountryProviderController;

use App\Http\Controllers\InformationController;

use App\Http\Controllers\IdValidations\PhoneValidationController;
use App\Models\DistrictModel;
use App\Models\Gen;
use App\Models\Market\MarketOutbox;
use App\Models\Market\MarketSubscription;
use App\Models\MarketInfoMessageCampaign;
use App\Models\NotificationCampaign;
use App\Models\NotificationMessage;
use App\Models\OnlineCourse;
use App\Models\OnlineCourseLesson;
use App\Models\OnlineCourseStudent;
use App\Models\OnlineCourseStudentBatchImporter;
use App\Models\ParishModel;
use App\Models\SubcountyModel;
use App\Models\User;
use App\Models\Utils;
use App\Models\Weather\WeatherSubscription;
use App\Traits\Notification;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('test', function () {
    Utils::renew_messages();
});
Route::get('boot-system', function () {
    //$m = MarketSubscription::find('82b28464-c790-41ed-b652-25ee7617350f');
    //$m->send_renew_message();
    Utils::process_weather_subs(true);
    Utils::process_market_subs(true);
    Utils::renew_messages(true);
    
    Utils::system_boot();  
    die('done');
});

Route::get('password-reset-link', function (Request $r) {
    $token = $r->tok;
    session([
        'reset_password_token' => $token,
    ]);
    $url = url('auth/password-reset-form');
    return redirect($url);
});

Route::get('auth/login', function () {
    $u = Admin::user();
    if ($u != null) {
        return redirect(url('/'));
    }

    return view('auth.login');
    // return view('welcome');
    //return redirect('/home');
});

Route::post('auth/password-reset-form', function (Request $r) {
    $password = trim($r->password);
    $password_1 = trim($r->password_1);

    //check if passwords match
    if ($password != $password_1) {
        return back()
            ->withErrors(['password' => 'Passwords do not match'])
            ->withInput();
    }

    //get reset_password_token from session
    $reset_password_token = $r->token;
    //get account
    $acc = \App\Models\User::where('reset_password_token', $reset_password_token)->first();
    if ($acc == null) {
        $message = "Invalid reset password token";
        return back()->withErrors(['token' => 'Invalid reset password token'])->withInput();
    }

    if ($reset_password_token == null || strlen($reset_password_token) < 3) {
        $message = "Invalid reset password token";
        return back()->withErrors(['password' => 'Invalid reset password token'])->withInput();
    }
    //check if password contains a letter and a number
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return back()
            ->withErrors(['password' => 'Password must contain a letter and a number'])
            ->withInput();
    }

    //check if password is at least 5 characters long
    if (strlen($password) < 5) {
        return back()
            ->withErrors(['password' => 'Password must be at least 5 characters long'])
            ->withInput();
    }

    //get account
    $acc = \App\Models\User::where('reset_password_token', $reset_password_token)->first();
    if ($acc == null) {
        $message = "Invalid reset password token";
        die($message);
    }

    //update password
    $acc->password = bcrypt($password);
    $acc->reset_password_token = null;
    $acc->has_changed_password = 'Yes';
    $acc->save();
    //success message in session
    session()->flash('success', 'Password updated successfully.');

    //login user
    Auth::login($acc, true);
    return redirect(url('/'));
});


Route::get('auth/password-reset-form', function () {
    //get reset_password_token from session
    $reset_password_token = session('reset_password_token');
    /* if ($reset_password_token == null || strlen($reset_password_token) < 3) {
        $message = "Invalid reset password token";
        die($message);
    }
    $acc = \App\Models\User::where('reset_password_token', $reset_password_token)->first();
    if ($acc == null) {
        $message = "Invalid reset password token";
        die($message);
    }
    $username = $acc->username;
    if ($username == null || strlen($username) < 2) {
        $username = $acc->phone;
    }
    if ($username == null || strlen($username) < 2) {
        $username = $acc->email;
    } */
    return view('auth.password-reset-form', [
        'token' => $reset_password_token
    ]);
});
/* Route::post('auth/login', function () {
    $post = $_POST;
    $email = $post['email'];
    $password = $post['password'];
    $user = \App\Models\User::where('email', $email)->first();
    if ($user == null) {
        $user = \App\Models\User::where('username', $email)->first();
        if ($user == null) {
            $phone = Utils::prepare_phone_number($email);
            if (Utils::phone_number_is_valid($phone)) {
                $user = \App\Models\User::where('phone', $phone)->first();
            }
        }
    }

    if ($user == null) {
        return back()
            ->withErrors(['email' => 'Invalid email or phone number'])
            ->withInput();
    }

    if (!password_verify($password, $user->password)) {
        die("Invalid password");
    }



    if ($this->guard()->attempt([
        'id' => $user->id,
        'password' => $password,
    ], true)) {
        die("failed");
    }

    $u = Admin::user();
    die("in as $u->name");
    die("success");
    dd($post);
    die("Login");
    // return view('welcome');
    //return redirect('/home');
});
 */

Route::get('market-info-message-campaigns-send-now', function () {

    $campaign = MarketInfoMessageCampaign::find($_GET['id']);
    if ($campaign == null) {
        die("Campaign not found");
    }
    if ($campaign->message_sent == 'Yes') {
        die("Messages already.");
    }
    $outboxes = MarketOutbox::where('market_info_message_campaign_id', $campaign->id)->get();
    if ($outboxes->count() == 0) {
        die("No outboxes found");
    }
    $i = 0;
    foreach ($outboxes as $outbox) {
        $i++;
        if ($outbox->status == 'Sent') {
            echo "$i. Already sent to {$outbox->recipient} <br>";
            continue;
        }
        $recipient = Utils::prepare_phone_number($outbox->recipient);
        if (!Utils::phone_number_is_valid($recipient)) {
            $outbox->status = 'Failed';
            $outbox->failure_reason = "Invalid phone number";
            $outbox->save();
            echo "$i. Invalid phone number: $recipient <br>";
            continue;
        }
        if ($outbox->status == 'Sent') {
            continue;
        }
        Utils::send_sms($recipient, $outbox->message);
        $outbox->status = 'Sent';
        $outbox->sent_at = Carbon::now(); 
        $outbox->save();
        echo "$i. Successfully sent to $recipient <br>";
    }
    $campaign->message_sent = "Yes";
    $campaign->save();
    die("<br>MESSAGES");
});
Route::get('sync-data', function () {
    foreach (OnlineCourseStudent::all() as $key => $s) {
        if ($s->progress >= 99) {
            $s->completion_status = 'Completed';
            $s->save();
        }
    }

    Utils::syncGroups();
    Utils::syncFarmers();
});

Route::get('send-notification-campaigns', function () {
    if (!isset($_GET['id'])) {
        die("ID not set");
    }
    $item = NotificationCampaign::find($_GET['id']);
    if ($item == null) {
        die("Notification not found");
    }
    if ($item->ready_to_send != 'Yes') {
        die("Not ready to send. STATUS: {$item->ready_to_send}");
    }
    try {
        $item->send_now();
        die("sent");
    } catch (\Exception $e) {
        die($e->getMessage());
    }
    // return view('welcome');
    //return redirect('/home');
});

Route::get('send-notification', function () {
    if (!isset($_GET['id'])) {
        die("ID not set");
    }
    $course_id = $_GET['id'];
    $course = NotificationMessage::find($course_id);
    if ($course == null) {
        die("Notification not found");
    }
    try {
        $course->send_now();
        die("sent");
    } catch (\Exception $e) {
        die($e->getMessage());
    }
    // return view('welcome');
    //return redirect('/home');
});

Route::get('send-inspector-notification', function () {
    if (!isset($_GET['id'])) {
        die("ID not set");
    }
    $course_id = $_GET['id'];
    $course = OnlineCourse::find($course_id);
    if ($course == null) {
        die("Course not found");
    }
    try {
        $course->send_inspector_notification();
        die("Success");
    } catch (\Exception $e) {
        die($e->getMessage());
    }
    // return view('welcome');
    //return redirect('/home');
});

Route::get('/course-student-batch-importer', function () {
    if (!isset($_GET['id'])) {
        die("ID not set");
    }
    $importer = OnlineCourseStudentBatchImporter::find($_GET['id']);
    if ($importer == null) {
        die("Importer not found");
    }
    $course = OnlineCourse::find($importer->online_course_id);
    if ($course == null) {
        die("Course not found");
    }
    $file = "storage/" . $importer->file_path;
    //check if file exists  
    if (!file_exists($file)) {
        die("File not found");
    }
    $data = [];
    $file = fopen($file, "r");
    if (!$file) {
        die("Error opening data file.");
    }
    while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
        $data[] = $column;
    }
    fclose($file);
    $i = 0;
    $success = 0;
    $failed = 0;
    $error_message = "";
    foreach ($data as $key => $value) {
        $i++;
        if ($key > 0) {
            if (!isset($value[0])) {
                $error_message .= "Row $i: NAME NOT SET\n";
                $failed++;
                continue;
            }
            if (!isset($value[1])) {
                $error_message .= "Row $i: PHONE NOT SET\n";
                $failed++;
                continue;
            }
            $name = $value[0];
            $phone = Utils::prepare_phone_number($value[1]);
            if (!Utils::phone_number_is_valid($phone)) {
                $error_message .= "Row $i: $name, has INVALID PHONE NUMBER: $phone\n";
                $failed++;
                continue;
            }

            $already_enrolled = OnlineCourseStudent::where([
                'online_course_id' => $course->id,
                'phone' => $phone
            ])->first();

            if ($already_enrolled != null) {
                $error_message .= "Row $i: $name, $phone, is already enrolled to this course.\n";
                $failed++;
                $success++;
                continue;
            }
            $farmer = new OnlineCourseStudent();
            $farmer->online_course_id = $course->id;
            $farmer->name = $name;
            $farmer->phone = $phone;
            $farmer->status = "Active";
            try {
                $farmer->save();
            } catch (\Exception $e) {
                $error_message .= "Row $i: $name, $phone, " . $e->getMessage() . "\n";
                $failed++;
                continue;
            }

            //$farmer->courses()->attach($course->id);
            $success++;
            continue;
        }
    }
    $importer->total = $i;
    $importer->success = $success;
    $importer->failed = $failed;
    $importer->error_message = $error_message;
    $importer->status = "Imported";
    $importer->save();
    die("done");
});
Route::get('/prepare-lessons', function () {
    foreach (OnlineCourse::all() as $key => $course) {
        $course->prepare_lessons();
    }
});


Route::get('/payment-test', function () {
    Utils::payment_status_test();
    die("<br>done.");
    Utils::payment_test();
    die("<br>done.");
});
Route::get('/prepare-data', function () {

    die("done");
    ini_set('memory_limit', '128M');
    ini_set('max_execution_time', -1);
    $path = ('./public/storage/Ug_Parishes.csv');
    if (!file_exists($path)) {
        dd("File not found. Please upload the file to storage/app/public/Ug_Parishes.csv");
    }
    $file = fopen($path, "r");
    if (!$file) {
        dd("Error opening data file.");
    }
    $data = [];
    while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
        $data[] = $column;
    }
    fclose($file);
    $parishes = [];
    $done = [];
    $i = 0;


    //parishes
    foreach ($data as $key => $value) {
        $i++;
        if ($key > 0) {
            if (!isset($value[2])) {
                echo "NOT SET";
                die();
            }
            $par_name = $value[3];
            $sub_name = $value[2];
            $dis_name = $value[0];
            $dis = DistrictModel::where('name', '=', '' . $dis_name . '')->first();
            if ($dis == null) {
                echo "District not found: $dis_name";
                dd();
            }
            $sub = SubcountyModel::where([
                'district_id' => $dis->id,
                'name' => $sub_name
            ])->first();
            if ($sub == null) {
                die("Subcounty not found: $sub_name");
            }

            $par = ParishModel::where([
                'subcounty_id' => $sub->id,
                'name' => $par_name
            ])->first();

            if ($par != null) {
                echo $i . ". Exists $par_name <br>";
                continue;
            }
            $par = new ParishModel();
            $par->name = $par_name;
            $par->subcounty_id = $sub->id;
            $par->district_id = $dis->id;
            $par->lng = $value[4];
            $par->lat = $value[5];
            $par->save();
            echo $i . ". Saved $par_name <br>";
            continue;
        }
    }



    //subcounties
    foreach ($data as $key => $value) {
        $i++;
        if ($key > 0) {
            if (!isset($value[2])) {
                echo "NOT SET";
                die();
            }
            $sub_name = $value[2];
            $dis_name = $value[0];
            $dis = DistrictModel::where('name', '=', '' . $dis_name . '')->first();
            if ($dis == null) {
                echo "District not found: $dis_name";
                dd();
            }
            $sub = SubcountyModel::where([
                'district_id' => $dis->id,
                'name' => $sub_name
            ])->first();
            if ($sub != null) {
                echo $i . ". Exists $sub_name <br>";
                continue;
            }
            $sub = new SubcountyModel();
            $sub->name = $sub_name;
            $sub->district_id = $dis->id;
            $sub->save();
            echo $i . ". Saved $sub_name <br>";
            continue;
        }
    }

    //districts
    foreach ($data as $key => $value) {
        if ($key > 0) {
            if (in_array($value[0], $done)) continue;
            $done[] = $value[0];
            $i++;
            $district = DistrictModel::where('name', '=', '' . $value[0] . '')->first();
            if ($district != null) {
                echo "$i. District found: " . $value[0] . "<br>";
                continue;
            }
            $dis = new DistrictModel();
            $dis->name = $value[0];
            $dis->save();
            echo "$i. Created: " . $value[0] . "<br>";
            continue;
        }
    }
    die("done");
    //DB::table('parishes')->insert($parishes);
    print("Hello /Users/mac/Desktop/Ug_Parishes.csv World");
});
Route::get('/gen', function () {
    die(Gen::find($_GET['id'])->do_get());
})->name("gen");
Route::get('/gen-form', function () {
    die(Gen::find($_GET['id'])->make_forms());
})->name("gen-form");


Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('logout-with-otp', [LoginController::class, 'logout'])->name("auth.otp.logout");
    // Two Factor Authentication
    Route::get('/login/verify', [OtpController::class, 'view'])->name("otp.view");
    Route::post('/login/check', [OtpController::class, 'check'])->name("otp.verify");
    Route::get('/login/resend', [OtpController::class, 'resend'])->name('otp.resend');
});
//Route::get('/', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth', 'otp_verification']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'user-management', 'as' => 'user-management.'], function () {
        Route::resource('users', UserController::class);
        Route::get('user/list', [UserController::class, 'list'])->name('users.list');
        Route::resource('roles', RoleController::class);
        Route::get('role/list', [RoleController::class, 'list'])->name('roles.list');
        Route::resource('permissions', PermissionController::class);
        Route::get('permission/list', [PermissionController::class, 'list'])->name('permissions.list');
        Route::resource('activity-logs', ActivityLogController::class);
        Route::resource('sessions', SessionLogController::class);
    });

    Route::group(['prefix' => 'farmers', 'as' => 'farmers.'], function () {
        Route::resource('farmers', FarmerController::class);
        Route::get('farmer/list', [FarmerController::class, 'list'])->name('farmers.list');
        Route::resource('groups', FarmerGroupController::class);
        Route::get('groups-by-organisation/{id}', [FarmerGroupController::class, 'getGroupsByOrganisaton']);
        Route::get('group/list', [FarmerGroupController::class, 'list'])->name('groups.list');

        Route::get('group/add-farmers', [FarmerGroupController::class, 'addFarmers'])->name('groups.add-farmers');
        Route::post('group/add-farmers', [FarmerGroupController::class, 'saveFarmers'])->name('groups.add-farmers.store');

        Route::get('groups/add-farmer/{group_id}', [FarmerGroupController::class, 'newGroupFarmer']);
        Route::post('group/add-farmer', [FarmerGroupController::class, 'saveFarmers'])->name('groups.add-farmer.store');

        Route::get('/maps', function (Request $request) {
            return response()->json(['success' => true, 'message' => 'Welcome'], 200);
        })->name('maps.index');
        Route::get('mapping/farmers', [FarmerMappingController::class, 'farmers'])->name('mapping.farmers');
        Route::get('maps/mapping/farmers-map', [FarmerMappingController::class, 'farmerMap'])->name('maps.farmers-map');
        Route::get('mapping/groups', [FarmerMappingController::class, 'groups'])->name('mapping.groups');
        Route::get('maps/mapping/groups-map', [FarmerMappingController::class, 'farmerGroupMap'])->name('maps.groups-map');
    });

    Route::group(['prefix' => 'village-agents', 'as' => 'village-agents.'], function () {
        Route::resource('agents', AgentController::class);
        Route::get('agent/list', [AgentController::class, 'list'])->name('agents.list');
        Route::get('agents-by-organisation/{id}', [AgentController::class, 'getAgentsByOrganisation']);

        Route::get('/maps', function (Request $request) {
            return response()->json(['success' => true, 'message' => 'Welcome'], 200);
        })->name('maps.index');

        Route::get('mapping/agents', [AgentMappingController::class, 'agents'])->name('mapping.agents');
        Route::get('maps/mapping/agents-map', [AgentMappingController::class, 'agentMap'])->name('maps.agents-map');

        Route::get('mapping/agents-farmers', [AgentMappingController::class, 'farmers'])->name('mapping.agents-farmers');
        Route::get('mapping/agents-groups', [AgentMappingController::class, 'groups'])->name('mapping.agents-groups');
    });

    Route::group(['prefix' => 'extension-officers', 'as' => 'extension-officers.'], function () {
        Route::resource('officers', ExtensionOfficerController::class);
        Route::get('officer/list', [ExtensionOfficerController::class, 'list'])->name('officers.list');
        Route::resource('positions', ExtensionOfficerPositionController::class);
        Route::get('position/list', [ExtensionOfficerPositionController::class, 'list'])->name('positions.list');

        Route::get('/maps', function (Request $request) {
            return response()->json(['success' => true, 'message' => 'Welcome'], 200);
        })->name('maps.index');
        Route::get('mapping/officers', [ExtensionOfficerMappingController::class, 'officers'])->name('mapping.officers');
        Route::get('maps/mapping/officers-map', [ExtensionOfficerMappingController::class, 'officerMap'])->name('maps.officers-map');
    });

    Route::group(['prefix' => 'organisations', 'as' => 'organisations.'], function () {
        Route::resource('organisations', OrganisationController::class);
        Route::get('organisation/list', [OrganisationController::class, 'list'])->name('organisations.list');
        Route::resource('permissions', OrganisationPermissionController::class);
        Route::get('permission/list', [OrganisationPermissionController::class, 'list'])->name('permissions.list');
        Route::resource('positions', OrganisationPositionController::class);
        Route::get('position/list', [OrganisationPositionController::class, 'list'])->name('positions.list');
        Route::resource('users', OrganisationUserController::class);
        Route::get('user/list', [OrganisationUserController::class, 'list'])->name('users.list');
        Route::get('organisations-by-country/{id}', [OrganisationController::class, 'getOrganisationsByCountry']);
    });

    Route::group(['prefix' => 'insurance', 'as' => 'insurance.'], function () {
        Route::resource('insurance-periods', InsurancePeriodController::class);
        Route::get('insurance-period/list', [InsurancePeriodController::class, 'list'])->name('insurance-periods.list');
        Route::resource('premium-options', InsurancePremiumOptionController::class);
        Route::get('premium-option/list', [InsurancePremiumOptionController::class, 'list'])->name('premium-options.list');
        Route::resource('calculator', InsuranceCalculatorController::class);
        Route::get('calculators/list', [InsuranceCalculatorController::class, 'list'])->name('calculator.list');
        Route::resource('loss-management', InsuranceLossManagementController::class);
        Route::get('loss-managements/list', [InsuranceLossManagementController::class, 'list'])->name('loss-management.list');
        Route::resource('full-coverage-rates', InsuranceFullCoverageRatesController::class);
        Route::get('full-coverage-rate/list', [InsuranceFullCoverageRatesController::class, 'list'])->name('full-coverage-rates.list');

        Route::resource('farmer-compensations', InsuranceFarmerCompensationController::class);
        Route::resource('farmer-compensation-logs', InsuranceCompensationHistoryController::class);
        Route::resource('agent-earnings', InsuranceAgentEarningController::class);
        Route::resource('agent-earning-logs', InsuranceAgentEarningHistoryController::class);
        Route::resource('subscriptions', InsuranceSubscriptionController::class);
        Route::get('subscription/crops/create', [InsuranceSubscriptionController::class, 'create_crops'])->name('subscriptions.create-crops');
        Route::get('subscription/livestock/create', [InsuranceSubscriptionController::class, 'create_livestock'])->name('subscriptions.create-livestock');
        Route::resource('transactions', InsuranceTransactionController::class);
        Route::resource('insurance-agents', InsuranceAgentsController::class);
        Route::resource('companies', InsuranceCompanyController::class);
        Route::resource('agent-commissions', InsuranceAgentCommisionController::class);
    });

    Route::group(['prefix' => 'questions', 'as' => 'questions.'], function () {
        Route::resource('questions', QuestionController::class);
        Route::get('question/list', [QuestionController::class, 'list'])->name('questions.list');
        Route::resource('responses', QuestionResponseController::class);
        Route::get('response/list', [QuestionResponseController::class, 'list'])->name('responses.list');
        Route::get('mapping/questions', [QuestionMappingController::class, 'questions'])->name('mapping.questions');

        Route::get('/maps', function (Request $request) {
            return response()->json(['success' => true, 'message' => 'Welcome'], 200);
        })->name('maps.index');
        Route::get('maps/mapping/questions-map', [QuestionMappingController::class, 'questionMap'])->name('maps.questions-map');
    });

    Route::group(['prefix' => 'alerts', 'as' => 'alerts.'], function () {
        Route::resource('alerts', AlertController::class);
        Route::get('alert/list', [AlertController::class, 'list'])->name('alerts.list');
        Route::get('mapping/alerts', [AlertMappingController::class, 'alerts'])->name('mapping.alerts');
        Route::get('single/create', [AlertController::class, 'create_single'])->name('single.create');
        Route::get('bulk/create', [AlertController::class, 'create_bulk'])->name('bulk.create');
        Route::get('keyword/create', [AlertController::class, 'create_keyword'])->name('keyword.create');
        Route::get('area/create', [AlertController::class, 'create_area'])->name('area.create');
        Route::get('enterprise/create', [AlertController::class, 'create_enterprise'])->name('enterprise.create');
        Route::get('user-group/create', [AlertController::class, 'create_user_group'])->name('user-group.create');
        Route::get('farmer-group/create', [AlertController::class, 'create_farmer_group'])->name('farmer-group.create');
        Route::get('group-member/create', [AlertController::class, 'create_group_member'])->name('group-member.create');

        Route::resource('outbreaks', OutbreakController::class);
        // Route::get('alert/list', [AlertController::class, 'list'])->name('alerts.list');
    });

    Route::group(['prefix' => 'trainings', 'as' => 'trainings.'], function () {
        Route::resource('resources', TrainingResouceController::class);
        Route::get('resource/list', [TrainingResouceController::class, 'list'])->name('resources.list');
        Route::resource('resource-sections', TrainingResouceSectionController::class);
        Route::get('resource-section/create/{resource_id}', [TrainingResouceSectionController::class, 'create'])->name('resource-sections.create');
        Route::resource('trainings', TrainingController::class);
        Route::get('training/list', [TrainingController::class, 'list'])->name('trainings.list');
        Route::resource('topics', ResourceTopicController::class);
        Route::get('topic/list', [ResourceTopicController::class, 'list'])->name('topics.list');
        Route::resource('sub-topics', ResourceSubTopicController::class);
        Route::get('sub-topic/list', [ResourceSubTopicController::class, 'list'])->name('sub-topics.list');
    });

    Route::group(['prefix' => 'e-learning', 'as' => 'e-learning.'], function () {
        Route::resource('instructors', InstructorController::class);
        Route::get('instructor/list', [InstructorController::class, 'massData'])->name('instructors.list');
        Route::get('instructor/picture/change/{id}', [InstructorController::class, 'changePicture'])->name('instructors.change-picture');
        Route::post('instructor/picture/upload', [InstructorController::class, 'storePicture'])->name('instructors.upload');

        Route::resource('instructor-invitations', InstructorInvitationController::class);
        Route::get('instructor-invitation/list', [InstructorInvitationController::class, 'massData'])->name('instructor-invitations.list');

        Route::resource('courses', CourseController::class);
        Route::get('course/list', [CourseController::class, 'massData'])->name('courses.list');
        Route::get('courses/file/{id}/{file}', [CourseController::class, 'editFile']);
        Route::put('courses/file/{id}/{file}', [CourseController::class, 'updateFile']);
        Route::get('courses/file/remove/{id}/{file}', [CourseController::class, 'deleteFile']);
        Route::get('courses/content/{courseId}', [CourseController::class, 'contents']);
        Route::get('courses/register/{courseId}', [CourseController::class, 'register']);
        Route::get('courses/deregister/{courseId}', [CourseController::class, 'deregister']);

        Route::resource('students', StudentController::class);
        Route::get('student/list', [StudentController::class, 'massData'])->name('students.list');
        Route::get('student/picture/change/{id}', [StudentController::class, 'changePicture'])->name('students.picture.change');
        Route::post('student/picture/upload', [StudentController::class, 'storePicture'])->name('students.picture.store');
        Route::get('student/upload', [StudentController::class, 'upload'])->name('students.upload');
        Route::post('student/picture/upload', [StudentController::class, 'import'])->name('students.upload.store');

        Route::resource('chapters', ChapterController::class);
        Route::get('courses/chapters/{courseId}', [ChapterController::class, 'index']);
        Route::get('courses/chapters/{courseId}/create', [ChapterController::class, 'create']);
        Route::get('course/chapters/list', [ChapterController::class, 'massData'])->name('chapters.list');

        Route::resource('weeks', WeekController::class);
        Route::get('courses/weeks/{courseId}', [WeekController::class, 'index']);
        Route::get('courses/weeks/{courseId}/create', [WeekController::class, 'create']);
        Route::get('course/weeks/list', [WeekController::class, 'massData'])->name('weeks.list');
        Route::get('courses/weeks/{courseId}/{weekId}/add-topic', [ChapterController::class, 'create']);

        Route::resource('lectures', LectureController::class);
        Route::get('courses/lectures/{courseId}', [LectureController::class, 'index']);
        Route::get('courses/lectures/{courseId}/create', [LectureController::class, 'create']);
        Route::get('course/lectures/list', [LectureController::class, 'massData'])->name('lectures.list');

        Route::get('courses/lectures/{lectureId}/topics/new', [LectureController::class, 'newTopic']);
        Route::post('courses/lectures/topics/store', [LectureController::class, 'storeTopic']);
        Route::get('courses/lectures/{topicId}/topics/show', [LectureController::class, 'showTopic']);

        Route::get('courses/lectures/topics/{topicId}/respond', [LectureController::class, 'newResponse']);
        Route::post('courses/lectures/topics/responses/store', [LectureController::class, 'storeResponse']);
        Route::get('courses/lectures/topics/{topicId}/subscribe', [LectureController::class, 'subscribeTopic']);
        Route::get('courses/lectures/topics/{topicId}/like', [LectureController::class, 'likeTopic']);
        Route::get('courses/lectures/topics/responses/{responseId}/like', [LectureController::class, 'likeTopicResponse']);

        Route::resource('announcements', AnnouncementController::class);
        Route::get('courses/board/{courseId}', [AnnouncementController::class, 'board']);
        Route::get('courses/announcements/{courseId}', [AnnouncementController::class, 'index']);
        Route::get('courses/announcements/{courseId}/create', [AnnouncementController::class, 'create']);
        Route::get('courses/announcements/{announcementId}/view', [AnnouncementController::class, 'single']);
        Route::get('course/announcements/list', [AnnouncementController::class, 'massData'])->name('announcements.list');
        Route::get('courses/announcements/{courseId}/subscribe', [AnnouncementController::class, 'subscribeAnnouncement']);

        Route::resource('resources', ResourcesController::class);
        Route::get('courses/sources/{courseId}', [ResourcesController::class, 'board']);
        Route::get('courses/resources/{courseId}', [ResourcesController::class, 'index']);
        Route::get('courses/resources/{courseId}/create', [ResourcesController::class, 'create']);
        Route::get('courses/resources/{announcementId}/view', [ResourcesController::class, 'single']);
        Route::get('course/resources/list', [ResourcesController::class, 'massData'])->name('resources.list');
        Route::get('courses/resources/{courseId}/subscribe', [ResourcesController::class, 'subscribeAnnouncement']);

        Route::get('courses/forums/{courseId}', [ForumController::class, 'index']);
        Route::get('courses/forums/{courseId}/topics/new', [ForumController::class, 'create']);
        Route::post('courses/forums/topics/store', [ForumController::class, 'store']);
        Route::get('courses/forums/{topicId}/topics/show', [ForumController::class, 'show']);
        Route::get('courses/forums/topics/{topicId}/respond', [ForumController::class, 'newResponse']);
        Route::post('courses/forums/topics/responses/store', [ForumController::class, 'storeResponse']);
        Route::get('courses/forums/topics/{topicId}/subscribe', [ForumController::class, 'subscribeTopic']);
        Route::get('courses/forums/topics/{topicId}/like', [ForumController::class, 'likeTopic']);
        Route::get('courses/forums/topics/responses/{responseId}/like', [ForumController::class, 'likeTopicResponse']);

        Route::resource('enrolled-students', CourseStudentsController::class);
        Route::get('courses/enrolled-students/{courseId}', [CourseStudentsController::class, 'index']);
        Route::get('courses/enrolled-students/{courseId}/create', [CourseStudentsController::class, 'create']);
        Route::get('course/enrolled-student/list', [CourseStudentsController::class, 'massData'])->name('enrolled-students.list');

        Route::get('courses/enrolled-students/{courseId}/{studentId}/enroll', [CourseStudentsController::class, 'store']);
        Route::get('courses/enrolled-students/{courseId}/{studentId}/delist', [CourseStudentsController::class, 'destroy']);
        Route::get('courses/enrolled-students/{courseId}/{studentId}/attendance', [CourseStudentsController::class, 'show']);

        Route::resource('assignments', AssignmentController::class);
        Route::get('courses/assignments/{courseId}', [AssignmentController::class, 'index']);
        Route::get('courses/assignments/{courseId}/create', [AssignmentController::class, 'create']);
        Route::get('course/assignments/list', [AssignmentController::class, 'massData'])->name('assignments.list');

        Route::resource('instructions', InstructionsController::class);
        Route::get('instruction/list', [InstructionsController::class, 'massData'])->name('instructions.list');

        Route::resource('course-instructions', CourseInstructionController::class);
        Route::get('courses/course-instructions/{courseId}', [CourseInstructionController::class, 'index']);
        Route::get('courses/course-instructions/{courseId}/{instructionId}/create', [CourseInstructionController::class, 'create']);
        Route::get('courses/course-instructions/{courseInstructionId}/{courseId}/{instructionId}/edit', [CourseInstructionController::class, 'edit']);
        Route::get('courses/course-instructions/{courseInstructionId}/{courseId}/{instructionId}/discard', [CourseInstructionController::class, 'destroy']);
        Route::get('course/course-instructions/list', [CourseInstructionController::class, 'massData'])->name('course-instructions.list');

        Route::resource('general-assignments', GeneralAssignmentController::class);
        Route::get('courses/general-assignments/{courseId}', [GeneralAssignmentController::class, 'index']);
        Route::get('courses/general-assignments/{courseId}/create', [GeneralAssignmentController::class, 'create']);
        Route::get('course/general-assignments/list', [GeneralAssignmentController::class, 'massData'])->name('general-assignments.list');

        Route::get('courses/analytics/{courseId}/overview', [CourseAnalyticsController::class, 'overview']);
        Route::get('courses/analytics/{courseId}/students', [CourseAnalyticsController::class, 'students']);
        Route::get('courses/analytics/{courseId}/lectures', [CourseAnalyticsController::class, 'lectures']);
        Route::get('courses/analytics/{courseId}/quiz', [CourseAnalyticsController::class, 'quiz']);
        Route::get('courses/analytics/{courseId}/questions', [CourseAnalyticsController::class, 'questions']);
        Route::get('course/analytics/student-attendance/list', [CourseAnalyticsController::class, 'studentAttendance'])->name('student-attendance.list');

        Route::resource('system-out-calls', SysteOutCallsController::class);

        Route::resource('messages', MessagesController::class);
        Route::get('message/list', [MessagesController::class, 'massData'])->name('messages.list');

        Route::resource('course-messages', CourseMessageController::class);
        Route::get('courses/course-messages/{courseId}', [CourseMessageController::class, 'index']);
        Route::get('courses/course-messages/{courseId}/{messageId}/create', [CourseMessageController::class, 'create']);
        Route::get('courses/course-messages/{courseInstructionId}/{courseId}/{messageId}/edit', [CourseMessageController::class, 'edit']);
        Route::get('courses/course-messages/{courseInstructionId}/{courseId}/{messageId}/discard', [CourseMessageController::class, 'destroy']);
        Route::get('course/course-messages/list', [CourseMessageController::class, 'massData'])->name('course-messages.list');
    });

    Route::group(['prefix' => 'market', 'as' => 'market.'], function () {
        Route::resource('subscription-keyword-prices', SubscriptionKeywordController::class);
        Route::resource('request-keyword-prices', OnRequestKeywordController::class);
        Route::resource('commodity-prices', CommodityPriceController::class);
        Route::get('commodity-price/list', [CommodityPriceController::class, 'list'])->name('commodity-prices.list');
        Route::get('commodity-price/upload', [CommodityPriceController::class, 'upload'])->name('commodity-prices.upload');
        Route::resource('subscriptions', MarketSubscriptionController::class);
        Route::get('subscription/list', [MarketSubscriptionController::class, 'list'])->name('subscriptions.list');
        Route::get('subscription/upload', [MarketSubscriptionController::class, 'upload'])->name('subscriptions.upload');
        Route::resource('transactions', MarketTransactionController::class);
        Route::get('transaction/list', [MarketTransactionController::class, 'list'])->name('transactions.list');
        Route::resource('commodities', MarketOutputProductController::class);
        Route::get('commodity/list', [MarketOutputProductController::class, 'list'])->name('commodities.list');
        Route::resource('markets', MarketsController::class);
        Route::get('market/list', [MarketsController::class, 'list'])->name('markets.list');
        Route::resource('outbox', MarketOutboxController::class);
        Route::get('outbox-message/list', [MarketOutboxController::class, 'list'])->name('outbox-messages.list');
        Route::resource('packages', MarketPackageController::class);
        Route::get('package/list', [MarketPackageController::class, 'list'])->name('packages.list');
        Route::get('package/messages/{package}', [MarketPackageController::class, 'messages']);
        Route::post('package/messages', [MarketPackageController::class, 'storeMessages'])->name('packages.messages.store');
    });

    Route::group(['prefix' => 'weather-info', 'as' => 'weather-info.'], function () {
        Route::resource('subscriptions', WeatherSubscriptionController::class);
        Route::get('subscription/list', [WeatherSubscriptionController::class, 'list'])->name('subscriptions.list');
        Route::get('subscription/upload', [WeatherSubscriptionController::class, 'upload'])->name('subscriptions.upload');
        Route::resource('transactions', WeatherTransactionController::class);
        Route::get('transaction/list', [WeatherTransactionController::class, 'list'])->name('transactions.list');
        Route::resource('outbox', WeatherOutboxController::class);
        Route::get('outbox-messages/list', [WeatherOutboxController::class, 'list'])->name('outbox-messages.list');
        Route::resource('conditions', WeatherConditionController::class);
        Route::get('condition/list', [WeatherConditionController::class, 'list'])->name('conditions.list');
        Route::resource('triggers', WeatherTriggertroller::class);
        Route::get('trigger/list', [WeatherTriggertroller::class, 'list'])->name('triggers.list');
    });

    Route::group(['prefix' => 'input-loans', 'as' => 'input-loans.'], function () {
        Route::resource('input-requests', InputRequestController::class);
        Route::get('subscription/upload', [WeatherSubscriptionController::class, 'upload'])->name('subscriptions.upload');
        Route::resource('lpos', PurchaseOrderController::class);
        Route::resource('loan-repayments', LoanRepaymentController::class);
        Route::resource('loan-settings', LoanSettingController::class);
        Route::get('loan-setting/list', [LoanSettingController::class, 'list'])->name('loan-settings.list');
        Route::resource('lpo-settings', LpoSettingController::class);
        Route::get('lpo-setting/list', [LpoSettingController::class, 'list'])->name('lpo-settings.list');
        Route::resource('loan-charges', LoanChargeController::class);
        Route::get('loan-charge/list', [LoanChargeController::class, 'list'])->name('loan-charges.list');
        Route::resource('input-commission-rates', InputCommissionRateController::class);
        Route::get('input-commission-rate/list', [InputCommissionRateController::class, 'list'])->name('input-commission-rates.list');
        Route::resource('microfinances', MicrofinanceController::class);
        Route::get('microfinance/list', [MicrofinanceController::class, 'list'])->name('microfinances.list');
        Route::resource('service-providers', ServiceProviderController::class);
        Route::resource('buyers', BuyerController::class);
        Route::get('buyer/list', [BuyerController::class, 'list'])->name('buyers.list');
        Route::resource('distributors', DistributorController::class);
        Route::get('distributor/list', [DistributorController::class, 'list'])->name('distributors.list');
        Route::resource('input-prices', InputPriceController::class);
        Route::get('input-price/list', [InputPriceController::class, 'list'])->name('input-prices.list');
        Route::resource('output-prices', OutputPriceController::class);
        Route::get('output-price/list', [OutputPriceController::class, 'list'])->name('output-prices.list');
        Route::resource('projects', InputProjectController::class);
        Route::get('project/list', [InputProjectController::class, 'list'])->name('projects.list');
        Route::resource('yield-estimations', YieldEstimationController::class);
        Route::get('yield-estimation/list', [YieldEstimationController::class, 'list'])->name('yield-estimations.list');
    });


    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::resource('countries', CountryController::class);
        Route::get('country/list', [CountryController::class, 'list'])->name('countries.list');
        Route::resource('locations', LocationController::class);
        Route::get('location/list', [LocationController::class, 'list'])->name('locations.list');
        Route::get('/location-by-country/{id}', [LocationController::class, 'getLocationsByCountry']);
        Route::resource('languages', LanguageController::class);
        Route::get('language/list', [LanguageController::class, 'list'])->name('languages.list');
        Route::resource('enterprises', EnterpriseController::class);
        Route::get('enterprise/list', [EnterpriseController::class, 'list'])->name('enterprises.list');
        Route::resource('modules', ModuleController::class);
        Route::get('module/list', [ModuleController::class, 'list'])->name('modules.list');
        Route::resource('country-modules', CountryModuleController::class);
        Route::get('country-module/list', [CountryModuleController::class, 'list'])->name('country-modules.list');
        Route::resource('seasons', SeasonController::class);
        Route::get('season/list', [SeasonController::class, 'list'])->name('seasons.list');
        Route::resource('markup', MarkupController::class);
        Route::get('markup/list', [MarkupController::class, 'list'])->name('markup.list');
        Route::resource('regions', RegionController::class);
        Route::get('season/list', [RegionController::class, 'list'])->name('regions.list');
        Route::resource('country-units', CountryUnitController::class);
        Route::get('country-unit/list', [CountryUnitController::class, 'list'])->name('country-units.list');
        Route::resource('keywords', KeywordController::class);
        Route::get('keyword/list', [KeywordController::class, 'list'])->name('keywords.list');
        Route::resource('enterprise-varieties', EnterpriseVarityController::class);
        Route::get('enterprise-variety/list', [EnterpriseVarityController::class, 'list'])->name('enterprise-varieties.list');
        Route::resource('enterprise-types', EnterpriseTypeController::class);
        Route::get('enterprise-type/list', [EnterpriseTypeController::class, 'list'])->name('enterprise-types.list');
        Route::resource('agro-products', AgroProductController::class);
        Route::get('agro-product/list', [AgroProductController::class, 'list'])->name('agro-products.list');
        Route::resource('success-responses', KeywordSuccessResponseController::class);
        Route::get('success-response/list', [KeywordSuccessResponseController::class, 'list'])->name('success-responses.list');
        Route::resource('failure-responses', KeywordFailureResponseController::class);
        Route::get('failure-response/list', [KeywordFailureResponseController::class, 'list'])->name('failure-responses.list');
        Route::resource('commission-rankings', CommissionRankingController::class);
        Route::get('commission-ranking/list', [CommissionRankingController::class, 'list'])->name('commission-rankings.list');
        Route::resource('units', UnitController::class);
        Route::get('unit/list', [UnitController::class, 'list'])->name('units.list');
        Route::resource('country-providers', CountryProviderController::class);
        Route::get('country-provider/list', [CountryProviderController::class, 'list'])->name('country-providers.list');
    });

    Route::group(['prefix' => 'validations', 'as' => 'validations.'], function () {
        Route::resource('phones', PhoneValidationController::class);
        Route::get('phone/list', [PhoneValidationController::class, 'list'])->name('phones.list');
    });

    Route::get('get_dialing_code_by_country', [CountryController::class, 'autoPickDialingCode']);
    Route::get('get-period-by-frequency', [InformationController::class, 'getFrequencyPeriod']);
});

Route::get('password/reset', function () {
    return view('auth.password-reset-email-form');
});
Route::get('password-request', function () {
    return view('auth.password-reset-email-form');
});
Route::post('password-request', function (Request $r) {
    $username = trim($r->input('username'));

    $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
    $isPhone = false;
    $phone = null;

    if (!$isEmail) {
        $phone = Utils::prepare_phone_number($username);
        //validate phone
        $isPhone = Utils::phone_number_is_valid($phone);
        if (!$isPhone) {
            return back()
                ->withErrors(['username' => 'Invalid email or phone number'])
                ->withInput();
        }
    }

    $acc = null;
    $username = null;
    if ($isEmail) {
        $username = trim($r->input('username'));
    } else {
        $username = $phone;
    }

    $acc = User::where('email', $username)->first();
    if ($acc == null) {
        $acc = User::where('phone', $username)->first();
        if ($acc == null) {
            $acc = User::where('email', $username)->first();
            if ($acc == null) {
                $acc = User::where('username', $username)->first();
            }
        }
    }
    if ($acc == null) {
        return back()
            ->withErrors(['username' => 'User not found. Please check your email or phone number.'])
            ->withInput();
    }

    $acc->sendPasswordReset();
    $success = "Password reset link has been sent to your email address. Please check your email.";
    //session success alert
    session([
        'reset_password_token' => null,
        'success' => $success
    ]);

    $url = url('auth/password-reset-form');
    //redirect to auth/password-reset-form
    return redirect($url);
});
