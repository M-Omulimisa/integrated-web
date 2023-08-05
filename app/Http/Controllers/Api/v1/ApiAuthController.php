<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\CountyModel;
use App\Models\DistrictModel;
use App\Models\FarmerQuestion;
use App\Models\FarmerQuestionAnswer;
use App\Models\Farmers\Farmer;
use App\Models\Farmers\FarmerGroup;
use App\Models\OrganisationJoiningRequest;
use App\Models\Organisations\Organisation;
use App\Models\RegionModel;
use App\Models\ResourceCategory;
use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Settings\Location;
use App\Models\SubcountyModel;
use App\Models\Training\Training;
use App\Models\Training\TrainingResource;
use App\Models\TrainingSession;
use App\Models\User;
use App\Models\Utils;
use App\Models\VillageModel;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthController extends Controller
{

    use ApiResponser;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $currentUrl = request()->path();
        $segments = explode('/', $currentUrl);
        $lastSegment = end($segments);
        if (!in_array($lastSegment, ['login', 'register'])) {
            $u = auth('api')->user();
            if ($u == null) {
                header('Content-Type: application/json');
                die(json_encode([
                    'code' => 0,
                    'message' => 'Unauthenticated',
                    'data' => null
                ]));
            }
        }
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $query = auth('api')->user();
        return $this->success($query, $message = "Profile details", 200);
    }


    public function organisation_joining_requests()
    {
        $u = auth('api')->user();
        return $this->success(OrganisationJoiningRequest::where(
            'user_id',
            $u->id
        )->get(), "Success");
    }

    public function farmer_groups()
    {
        $u = auth('api')->user();
        return $this->success(FarmerGroup::where([
            'organisation_id' => $u->organisation_id
        ])->get(), "Success");
    }


    public function farmers_create(Request $r)
    {
        $u = auth('api')->user();
        $f = new Farmer();
        $f->agent_id = $u->id;
        $f->created_by_user_id = $u->id;
        $f->created_by_agent_id = $u->id;
        $f->organisation_id = $u->organisation_id;
        $f->farmer_group_id = $r->farmer_group_id;
        $f->first_name = $r->first_name;
        $f->country_id = $r->country_id;
        $f->national_id_number = $r->national_id_number;
        $f->gender = $r->gender;
        $f->education_level = $r->education_level;
        $f->year_of_birth = $r->year_of_birth;
        $f->phone = $r->phone;
        $f->email = $r->email;
        $f->is_your_phone = $r->is_your_phone;
        $f->is_mm_registered = $r->is_mm_registered;
        $f->other_economic_activity = $r->other_economic_activity;
        $f->location_id = $r->location_id;
        $f->address = $r->address;
        $f->latitude = $r->latitude;
        $f->longitude = $r->longitude;
        $f->password = password_hash('4321', PASSWORD_DEFAULT);
        $f->farming_scale = $r->farming_scale;
        $f->land_holding_in_acres = $r->land_holding_in_acres;
        $f->land_under_farming_in_acres = $r->land_under_farming_in_acres;
        $f->ever_bought_insurance = $r->ever_bought_insurance;
        $f->ever_received_credit = $r->ever_received_credit;
        $f->status = 'Pending';
        $f->poverty_level = $r->poverty_level;
        $f->food_security_level = $r->food_security_level;
        $f->marital_status = $r->marital_status;
        $f->family_size = $r->family_size;
        $f->farm_decision_role = $r->farm_decision_role;
        $f->is_pwd = $r->is_pwd;
        $f->is_refugee = $r->is_refugee;
        $f->date_of_birth = $r->date_of_birth;
        $f->age_group = $r->age_group;
        $f->language_preference = $r->language_preference;
        $f->phone_number = $r->phone_number;
        $f->phone_type = $r->phone_type;
        $f->preferred_info_type = $r->preferred_info_type;
        $f->home_gps_latitude = $r->home_gps_latitude;
        $f->home_gps_longitude = $r->home_gps_longitude;
        $f->village = $r->village;
        $f->street = $r->street;
        $f->house_number = $r->house_number;
        $f->land_registration_numbers = $r->land_registration_numbers;
        $f->labor_force = $r->labor_force;
        $f->equipment_owned = $r->equipment_owned;
        $f->livestock = $r->livestock;
        $f->crops_grown = $r->crops_grown;
        $f->has_bank_account = $r->has_bank_account;
        $f->has_mobile_money_account = $r->has_mobile_money_account;
        $f->payments_or_transfers = $r->payments_or_transfers;
        $f->financial_service_provider = $r->financial_service_provider;
        $f->has_credit = $r->has_credit;
        $f->loan_size = $r->loan_size;
        $f->loan_usage = $r->loan_usage;
        $f->farm_business_plan = $r->farm_business_plan;
        $f->covered_risks = $r->covered_risks;
        $f->insurance_company_name = $r->insurance_company_name;
        $f->insurance_cost = $r->insurance_cost;
        $f->repaid_amount = $r->repaid_amount;
        $f->photo = $r->photo;
        $image = "";
        if (!empty($_FILES)) {
            try {
                $image = Utils::upload_images_2($_FILES, true);
            } catch (Throwable $t) {
                $image = "no_image.jpg";
            }
        }
        $f->photo = $image;
        $f->save();
        return $this->success($f, "Success");
    }

    public function farmers()
    {
        $u = auth('api')->user();
        return $this->success(Farmer::where([
            'organisation_id' => $u->organisation_id
        ])->get(), "Success");
    }

    public function countries()
    {
        return $this->success(Country::where([])->get(), "Success");
    }
    public function locations()
    {
        return $this->success(Location::where([])->get(), "Success");
    }

    public function districts()
    {
        return $this->success(DistrictModel::where([])->get(), "Success");
    }

    public function regions()
    {
        return $this->success(RegionModel::where([])->get(), "Success");
    }
    public function subcounties()
    {
        return $this->success(SubcountyModel::where([])->get(), "Success");
    }

    public function villages()
    {
        return $this->success(
            VillageModel::all('id', 'name', 'parish_id'),
            "Success"
        );
    }

    public function counties()
    {
        return $this->success(
            CountyModel::all('id', 'name', 'district_id'),
            "Success"
        );
    }
    /* 
            "id": 2,
            "": "ABEIBUTI",
            "parish_id": 441,
            "user_id": 0,
            "created": "0000-00-00 00:00:00",
            "changed": 0
    */

    public function languages()
    {
        return $this->success(Language::where([])->get(), "Success");
    }

    public function farmer_questions()
    {
        $data = FarmerQuestion::where([])->get();
        $data->append(
            [
                'user_text',
                'user_photo',
                'district_text',
                'answers_count'
            ]
        );
        return $this->success($data, "Success");
    }
    public function farmer_question_answers()
    {
        $data = FarmerQuestionAnswer::where([])->get();
        $data->append(
            [
                'user_text',
                'user_photo',
            ]
        );
        return $this->success($data, "Success");
    }

    public function trainings()
    {
        return $this->success(Training::where([
            'organisation_id' => auth('api')->user()->organisation_id
        ])->get(), "Success");
    }
    public function training_sessions()
    {
        return $this->success(TrainingSession::where([
            'organisation_id' => auth('api')->user()->organisation_id
        ])->get(), "Success");
    }

    public function training_session_post(Request $r)
    {

        return $this->success(null, "Success");

        $u = auth('api')->user();
        if ($r->name != null) {
            $u->name = $r->name;
        }

        $u_1 = User::where([
            'email' => $r->email,
        ])->first();

        return $this->success(TrainingSession::where([
            'organisation_id' => auth('api')->user()->organisation_id
        ])->get(), "Success");
    }

    public function my_roles()
    {
        $u = auth('api')->user();
        $data = [];
        foreach (AdminRoleUser::where([
            'user_id' => $u->id,
        ])->get() as $key => $role) {
            if ($role->role == null) continue;
            $d['role_id'] = $role->role_id;
            $d['role_name'] = $role->role->name;
            $data[] = $d;
        }
        return $this->success($data, "Success");
    }

    public function resources()
    {
        $u = auth('api')->user();
        return $this->success(TrainingResource::where([])->get(), "Success");
    }
    public function resources_categpries()
    {
        $res = [];
        foreach (ResourceCategory::where([])->get() as $key => $value) {
            $value->count = TrainingResource::where('resource_category_id', $value->id)->count();
            $res[] = $value;
        }
        return $this->success($res, "Success");
    }

    public function organisations()
    {
        return $this->success(Organisation::where([])->get(), "Success");
    }

    public function farmer_questions_create(Request $r)
    {
        if ($r->body == null && empty($_FILES)) return $this->error("Question is required.");
        if ($r->category == null) return $this->error("Category is required.");

        $images = [];
        if (!empty($_FILES)) {
            try {
                $images = Utils::upload_images_2($_FILES, false);
            } catch (Throwable $t) {
                $images = [];
            }
        }

        $f = new FarmerQuestion();
        if (is_array($images)) {
            if (isset($images[0])) {
                if (Utils::isImageFile(Utils::docs_root() . '/storage/images/' . $images[0])) {
                    $f->photo = 'images/' . $images[0];
                } else {
                    $f->audio = 'images/' . $images[0];
                }
            }
            if (isset($images[1])) {
                if (Utils::isImageFile(Utils::docs_root() . '/storage/images/' . $images[1])) {
                    $f->photo = 'images/' .$images[1];
                } else {
                    $f->audio = $images[1];
                }
            }
        }

        $u = auth('api')->user();
        $f->user_id = $u->id;
        $f->body = $r->body;
        $f->category = $r->category;
        $f->phone = $r->phone;
        $f->sent_via = $r->sent_via;
        $f->answered = 'no';
        $f->video = $r->video;
        $f->views = 0;
        try {
            $f->save();
        } catch (\Throwable $t) {
            return $this->error($t->getMessage());
        }
        return $this->success($f, "Question submitted successfully.");
    }
    public function update_profile(Request $r)
    {
        $u = auth('api')->user();
        if ($r->name != null) {
            $u->name = $r->name;
        }

        $u_1 = User::where([
            'email' => $r->email,
        ])->first();

        if ($u_1 != null) {
            if ($u_1->id != $u->id) {
                return $this->error("Email address already taken.");
            }
        }

        if ($r->email != null) {
            $u->email = $r->email;
        }
        if ($r->phone != null) {
            $u->phone = $r->phone;
        }

        $image = "";
        if (!empty($_FILES)) {
            try {
                $image = Utils::upload_images_2($_FILES, true);
            } catch (Throwable $t) {
                $image = "no_image.jpg";
            }
        }
        $u->photo = $image;

        if ($u->save()) {
            return $this->success($u, "Profile updated successfully.");
        } else {
            return $this->error("Failed to update profile.");
        }
    }
    public function organisation_joining_request_post(Request $r)
    {
        if ($r->organisation_id == null) return $this->error("Organisation ID is required.");
        if (!Organisation::find($r->organisation_id)) return $this->error("Invalid organisation ID.");
        if (OrganisationJoiningRequest::where(
            'user_id',
            auth('api')->user()->id
        )->where(
            'organisation_id',
            $r->organisation_id
        )->count() > 0) {
            return $this->error("You have already sent a request to join this organisation.");
        }

        $u = auth('api')->user();
        $req = OrganisationJoiningRequest::where(
            'user_id',
            $u->id
        )->first();
        if ($req == null) {
            $req = new OrganisationJoiningRequest();
            $req->user_id = $u->id;
        }
        $req->organisation_id = $r->organisation_id;
        $req->status = 'Pending';
        if ($req->save()) {
            return $this->success($req, "Request submitted successfully.");
        } else {
            return $this->error("Failed to send request.");
        }
    }


    public function login(Request $r)
    {

        if ($r->username == null) {
            return $this->error('Phone number or Email Address is required.');
        }

        if ($r->password == null) {
            return $this->error('Password is required.');
        }

        $r->username = trim($r->username);

        $u = User::where('email', $r->username)
            ->orWhere('phone', $r->username)
            ->orWhere('username', $r->username)
            ->first();


        if ($u == null) {
            return $this->error('User account not found.');
        }

        $token = auth('api')->attempt([
            'id' => $u->id,
            'password' => trim($r->password),
        ]);


        if ($token == null) {
            return $this->error('Wrong credentials.');
        }


        //auth('api')->factory()->setTTL(Carbon::now()->addMonth(12)->timestamp);

        JWTAuth::factory()->setTTL(60 * 24 * 30 * 365);


        $token = auth('api')->attempt([
            'id' => $u->id,
            'password' => trim($r->password),
        ]);


        if ($token == null) {
            return $this->error('Wrong credentials.');
        }
        $u->token = $token;
        $u->remember_token = $token;
        $u->roles_text = json_encode($u->roles);

        return $this->success($u, 'Logged in successfully.');
    }

    public function register(Request $r)
    {
        if ($r->name == null) {
            return $this->error('Name is required.');
        }

        if ($r->email == null) {
            return $this->error('Email address is required.');
        }

        if ($r->password == null) {
            return $this->error('Password is required.');
        }

        $u = User::where('email', $r->email)
            ->orWhere('phone', $r->email)
            ->orWhere('username', $r->email)
            ->first();
        if ($u != null) {
            return $this->error('User with same email address already exists.');
        }

        $u = User::where('email', $r->phone)
            ->orWhere('phone', $r->phone)
            ->orWhere('username', $r->phone)
            ->first();
        if ($u != null) {
            return $this->error('User with same phone number already exists.');
        }

        $user = new User();
        $user->name = $r->name;
        $user->phone = $r->phone;
        $user->email = $r->email;
        $user->username = $r->email;
        $user->photo = null;
        $user->password = password_hash(trim($r->password), PASSWORD_DEFAULT);
        try {
            $user->save();
        } catch (\Throwable $th) {
            return $this->error('Failed to create account because ' . $th->getMessage());
        }

        $new_user = User::where(['email' => $r->email])->first();
        if ($new_user == null) {
            return $this->error('Account created successfully but failed to log you in.');
        }
        Config::set('jwt.ttl', 60 * 24 * 30 * 365);

        $token = auth('api')->attempt([
            'id' => $new_user->id,
            'password' => $r->password,
        ]);

        $new_user->token = $token;
        $new_user->remember_token = $token;
        return $this->success($new_user, 'Account created successfully.');
    }
}
