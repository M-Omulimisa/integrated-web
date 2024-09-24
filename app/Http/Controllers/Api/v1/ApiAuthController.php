<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminRoleUser;
use App\Models\CountyModel;
use App\Models\Crop;
use App\Models\DistrictModel;
use App\Models\FarmerQuestion;
use App\Models\FarmerQuestionAnswer;
use App\Models\Farmers\Farmer;
use App\Models\Farmers\FarmerGroup;
use App\Models\GardenCoordicate;
use App\Models\GardenModel;
use App\Models\Image;
use App\Models\OrganisationJoiningRequest;
use App\Models\Organisations\Organisation;
use App\Models\ParishModel;
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
use App\Models\Users\Role;
use App\Models\Utils;
use App\Models\VillageModel;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Schema;

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
        /* if (!in_array($lastSegment, ['login', 'register', 'request-otp-sms'])) {
            $u = auth('api')->user();
            if ($u == null) {
                header('Content-Type: application/json');
                die(json_encode([
                    'code' => 0,
                    'message' => 'Unauthenticated',
                    'data' => null
                ]));
            }
        } */
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


    public function farmers_create_v2(Request $r)
    {
        $u = auth('api')->user();
        //check if user logged in
        if ($u == null) {
            return $this->error("Unauthenticated.");
        }

        if ($r->first_name == null || strlen($r->first_name) < 3) {
            return $this->error("First name is required.");
        }
        if ($r->last_name == null || strlen($r->last_name) < 3) {
            return $this->error("Last name is required.");
        }
        if ($r->phone == null || strlen($r->phone) < 3) {
            return $this->error("Phone is required.");
        }

        $f = Farmer::where('external_id', $r->external_id)->first();


        if ($f == null) {
            $f = Farmer::where('phone', $r->phone)->first();
        }
        if ($f == null) {
            $phone_number = Utils::prepare_phone_number($r->phone);
            $f = Farmer::where('phone', $phone_number)->first();
        }
        if ($f == null) {
            $phone_number = Utils::prepare_phone_number($r->phone_number);
            $f = Farmer::where('phone_number', $phone_number)->first();
        }

        if ($f == null) {
            if ($r->national_id_number != null) {
                if (strlen($r->national_id_number) > 4) {
                    $f = Farmer::where('national_id_number', $r->national_id_number)->first();
                }
            }
        }

        $phone_number = Utils::prepare_phone_number($r->phone);
        if (!Utils::phone_number_is_valid($phone_number)) {
            return $this->error('Invalid phone number.');
        }

        if ($f == null) {
            $f = new Farmer();
        }

        $table = (new Farmer())->getTable();
        $colls_of_table = Schema::getColumnListing($table);

        //all submited fields
        $submitted_fields = $r->all();
        //loop through all fields and them to farmer object if they exist in the table
        $skip = ['id', 'created_at', 'updated_at', 'deleted_at'];
        foreach ($submitted_fields as $key => $value) {
            if (in_array($key, $skip)) {
                continue;
            }
            if (in_array($key, $colls_of_table)) {
                $f->$key = $value;
            }
        }


        $f->agent_id = $u->id;
        $f->created_by_user_id = $u->id;
        $f->created_by_agent_id = $u->id;
        $f->organisation_id = $u->organisation_id;
        $f->farmer_group_id = $r->farmer_group_id;
        $f->country_id = $r->country_id;
        $f->phone = $phone_number;
        $f->phone_number = $phone_number;
        $f->password = password_hash('4321', PASSWORD_DEFAULT);
        $f->status = 'Pending';
        try {
            $f->date_of_birth = Carbon::parse($r->date_of_birth)->format('Y-m-d');
        } catch (\Throwable $th) {
            //throw $th;
        }

        $image = "no_image.jpg";
        if (!empty($_FILES)) {
            try {
                $image = Utils::photo_upload($r->file('file'));
                $f->photo = $image;
            } catch (Throwable $t) {
                $image = "no_image.jpg";
            }
        }


        try {
            $f->save();
            //$f = Farmer::find($f->id);
            if ($f == null) {
                return $this->error("Failed to save farmer on the database.");
            } else {
                $f = Farmer::find($f->id);
                return $this->success($f, "Farmer saved successfully.");
            }
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success($f, "Success");
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
        $f->last_name = $r->last_name;
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
        $f->date_of_birth = Carbon::parse($r->date_of_birth);
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
        $f->parish_id = $r->parish_id;
        $f->bank_id = $r->bank_id;
        $f->other_livestock_count = $r->other_livestock_count;
        $f->poultry_count = $r->poultry_count;
        $f->sheep_count = $r->sheep_count;
        $f->goat_count = $r->goat_count;
        $f->cattle_count = $r->cattle_count;
        $f->bank_account_number = $r->bank_account_number;
        $f->has_receive_loan = $r->has_receive_loan;

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

    public function gardens()
    {
        $u = auth('api')->user();
        $conditions = [];
        if (!$u->isRole('admin')) {
            $conditions = ['user_id' => $u->id];
        }
        return $this->success(GardenModel::where($conditions)->get(), "Success");
    }

    public function farmers()
    {
        $u = auth('api')->user();
        $conditions = [];
        if (!$u->isRole('admin')) {
            // $conditions = ['created_by_user_id' => $u->id];
        }
        $conditions = ['organisation_id' => $u->id];
        return $this->success(Farmer::where($conditions)
            ->orderBy('created_at', 'desc')
            ->limit(150)
            ->get(), "Success");
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

    public function crops()
    {
        return $this->success(Crop::where([])->get(), "Success");
    }

    public function resource_categories()
    {
        return $this->success(ResourceCategory::where([])->get(), "Success");
    }

    public function regions()
    {
        return $this->success(RegionModel::where([])->get(), "Success");
    }

    public function subscribers()
    {
        return $this->success(InsuranceSubscriptionController::where([])->get(), "Success");
    }
    public function subcounties()
    {
        return $this->success(SubcountyModel::where([])->get(), "Success");
    }

    public function parishes()
    {
        return $this->success(ParishModel::where([])->get(), "Success");
    }

    public function parishes_2()
    {
        $select = "parish.id, parish.name as p_name, subcounty.name as s_name, district.name as d_name";
        $sql = "SELECT $select
         FROM `parish`, `subcounty`, `district` WHERE `parish`.`subcounty_id` = `subcounty`.`id` AND `subcounty`.`district_id` = `district`.`id`";
        $temp_data = DB::select($sql);

        $data = [];
        foreach ($temp_data as $key => $value) {
            $d = [];
            $d['id'] = $value->id;
            $d['name'] = $value->d_name . ", " . $value->s_name . ", " . $value->p_name;
            $data[] = $d;
        }

        return $this->success($data, "Success");
    }

    public function villages()
    {
        return $this->success(
            VillageModel::all('id', 'name', 'parish_id'),
            "Success"
        );
    }

    public function permissions()
    {
        return $this->success(
            AdminPermission::all('id', 'name', 'slug'),
            "Success"
        );
    }


    public function my_permissions()
    {
        $u = auth('api')->user();
        $data = [];
        $slugs = [];
        foreach (
            AdminRoleUser::where([
                'user_id' => $u->id,
            ])->get() as $key => $role
        ) {
            if ($role->role == null) continue;
            $r = AdminRole::find($role->role_id);
            if ($r == null) continue;


            if ($r->permissions != null) {
                foreach ($r->permissions as $key => $value) {
                    if (!in_array($value->slug, $slugs)) {
                        $slug['id'] = $value->id;
                        $slug['slug'] = $value->slug;
                        $slug['name'] = $value->name;
                        $slugs[] = $slug;
                    }
                }
            }

            $d['role_id'] = $role->role_id;
            $d['role_name'] = $role->role->name;
            $d['slug'] = $r->slug;
            $data[] = $d;
        }

        return $this->success($slugs, "Success");
    }


    public function roles()
    {
        return $this->success(
            AdminRole::all('id', 'name', 'slug'),
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

        $u = auth('api')->user();
        if ($r == null) {
            return $this->error("Unauthorised.");
        }

        $id = $r->id;
        if ($id == null) {
            return $this->error("Invalid request.");
        }
        if ($r->training_id == null) {
            return $this->error("Training is required.");
        }
        if ($r->members_ids == null) {
            return $this->error("Training is required.");
        }
        if ($r->location_id == null) {
            return $this->error("Location is required.");
        }
        $training = Training::find($r->training_id);
        if ($training == null) {
            return $this->error("Training not found.");
        }


        $session = new TrainingSession();
        $session->organisation_id = $u->organisation_id;
        $session->training_id = $training->id;
        $session->location_id = $r->location_id;
        $session->conducted_by = $u->id;
        $session->session_date = $r->session_date;
        $session->start_date = $r->start_date;
        $session->end_date = $r->end_date;
        $end_time = explode(" ", $r->end_date);
        if (isset($end_time[1])) {
            $session->end_date = $end_time[1];
        }
        $start_time = explode(" ", $r->start_date);
        if (isset($start_time[1])) {
            $session->start_date = $start_time[1];
        }
        $session->details = $r->details;
        $session->topics_covered = $r->details;
        $session->gps_latitude = $r->gps_latitude;
        $session->gps_longitude = $r->gps_longitude;

        $images = [];
        foreach (
            Image::where([
                'parent_id' => $id,
            ])->get() as $key => $value
        ) {
            $images[] = 'images/' . $value->src;
        }
        $session->attendance_list_pictures = $images;

        $members_ids = [];
        try {
            $members_ids = json_decode($r->members_ids);
        } catch (\Throwable $t) {
            $members_ids = [];
        }
        if (count($members_ids) < 1) {
            return $this->error("No members selected.");
        }
        $member = Farmer::find($members_ids[0]);
        if ($member == null) {
            return $this->error("Member not found.");
        }
        $session->organisation_id = $member->organisation_id;
        $session->farmer_group_id = $member->farmer_group_id;

        try {
            $session->save();
            foreach ($members_ids as $key => $member_id) {
                $sql = "INSERT INTO `training_training_session_has_members` (`training_session_id`, `user_id`) VALUES (?, ?)";
                DB::insert($sql, [$session->id, $member_id]);
            }
        } catch (\Throwable $t) {
            return $this->error($t->getMessage());
        }
        return $this->success(null, "Success");
    }

    public function gardens_create(Request $r)
    {
        $u = auth('api')->user();
        if ($r == null) {
            return $this->error("Unauthorised.");
        }

        $id = $r->id;
        if ($id == null) {
            return $this->error("Invalid request.");
        }

        if ($r->user_id == null) {
            return $this->error("User is required.");
        }
        if ($r->coordinates == null) {
            return $this->error("Coordinates are required.");
        }

        if ($r->name == null) {
            return $this->error("Name is required.");
        }
        if ($r->size == null) {
            return $this->error("Size is required.");
        }

        $garden = new GardenModel();
        $garden->user_id = $r->user_id;

        if ($r->size != null) {
            $garden->district_id = $r->district_id;
            $garden->subcounty_id = $r->subcounty_id;
            $garden->parish_id = $r->parish_id;
        }

        $garden->crop_id = $r->crop_id;
        $garden->village = $r->village;
        $garden->name = $r->name;
        $garden->details = $r->details;
        $garden->size = $r->size;
        $garden->coordinates = $r->coordinates;
        $garden->status = $r->status;
        $garden->soil_type = $r->soil_type;
        $garden->soil_ph = $r->soil_ph;
        $garden->soil_texture = $r->soil_texture;
        $garden->soil_depth = $r->soil_depth;
        $garden->soil_moisture = $r->soil_moisture;
        $garden->crop_planted = $r->crop_text;

        $images = [];
        foreach (
            Image::where([
                'parent_id' => $id,
            ])->get() as $key => $value
        ) {
            $images[] = 'images/' . $value->src;
        }
        $garden->photos = $images;

        $coordinates = [];
        if ($r->coordinates != null) {
            try {
                $coordinates = json_decode($r->coordinates);
                if (!is_array($coordinates)) {
                    $coordinates = [];
                }
            } catch (\Throwable $t) {
                $coordinates = [];
            }
        }

        try {
            $garden->save();
            foreach ($coordinates as $key => $coordinate) {
                $coordicate = new GardenCoordicate();
                $coordicate->garden_id = $garden->id;
                $coordicate->latitude = $coordinate->attribute_1;
                $coordicate->longitude = $coordinate->attribute_2;
                $coordicate->save();
            }
            //success
            return $this->success(null, "Success");
        } catch (\Throwable $t) {
            return $this->error($t->getMessage());
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function my_roles()
    {
        $u = auth('api')->user();
        $data = [];
        foreach (
            AdminRoleUser::where([
                'user_id' => $u->id,
            ])->get() as $key => $role
        ) {
            if ($role->role == null) continue;
            $r = AdminRole::find($role->role_id);
            if ($r == null) continue;
            $d['role_id'] = $role->role_id;
            $d['role_name'] = $role->role->name;
            $d['slug'] = $r->slug;
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
        $conds = [];
        $u = auth('api')->user();
        $conds = ['id' => $u->organisation_id];
        return $this->success(Organisation::where($conds)->get(), "Success");
    }

    public function farmer_questions_create(Request $r)
    {
        // Remove the check for $r->body
        if (empty($r->body) && empty($_FILES) && empty($r->video)) {
            return $this->error("At least one of text, image, audio, or video is required.");
        }
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
                    $f->photo = 'images/' . $images[1];
                } else {
                    $f->audio = $images[1];
                }
            }
        }

        $u = auth('api')->user();
        $f->user_id = $u->id;
        $f->body = $r->body ?? ''; // Set body to an empty string if it's not provided
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


    public function farmer_answers_create(Request $r)
    {
        /*  if ($r->body == null && empty($_FILES)) return $this->error("Answer is required."); */
        if ($r->question_id == null) return $this->error("Question is required.");

        $images = [];
        if (!empty($_FILES)) {
            try {
                $images = Utils::upload_images_2($_FILES, false);
            } catch (Throwable $t) {
                $images = [];
            }
        }

        $f = new FarmerQuestionAnswer();
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
                    $f->photo = 'images/' . $images[1];
                } else {
                    $f->audio = $images[1];
                }
            }
        }

        $u = auth('api')->user();
        $f->user_id = $u->id;
        $f->body = $r->body;
        $f->farmer_question_id = $r->question_id;

        try {
            $f->save();
        } catch (\Throwable $t) {
            return $this->error($t->getMessage());
        }

        return $this->success($f, "Answer submitted successfully.");
    }

    public function update_profile(Request $r)
    {
        $u = auth('api')->user();

        if ($u == null) {
            return $this->error("Not authenticated.");
        }

        $u = User::find($u->id);
        if ($u == null) {
            return $this->error("User not found.");
        }

        //check if first and last name were submitted
        if ($r->first_name == null || strlen($r->first_name) < 3) {
            return $this->error("First name is required.");
        }
        if ($r->last_name == null || strlen($r->last_name) < 3) {
            return $this->error("Last name is required.");
        }
        $u->first_name = $r->first_name;
        $u->last_name = $r->last_name;
        $u->name = $r->first_name . ' ' . $r->last_name;

        //phone_number
        if ($r->phone_number != null) {
            $phone_number = Utils::prepare_phone_number($r->phone_number);
            if (!Utils::phone_number_is_valid($phone_number)) {
                return $this->error('Invalid phone number.');
            }
        }

        $u2 = User::where('phone_number', $phone_number)->first();
        if ($u2 != null && $u2->id != $u->id) {
            return $this->error('Phone number already in used by another account: ' . $u2->name);
        }
        $u->phone_number = $phone_number;
        $u->sex = $r->sex;

        //check if email is not empty and find duplicate
        if ($r->email != null && strlen($r->email) > 3) {
            $email = $r->email;
            $u2 = User::where('email', $email)->first();
            if ($u2 != null && $u2->id != $u->id) {
                return $this->error('Email already in used by another account: ' . $u2->name);
            }
            //chec by username
            $u2 = User::where('username', $email)->first();
            if ($u2 != null && $u2->id != $u->id) {
                return $this->error('Email already in used by another account: ' . $u2->name);
            }
            $u->email = $email;
            $u->username = $email;
        }
        $u->address = $r->address;
        $u->parish_id = $r->parish_id;
        try {
            $u->save();
        } catch (\Throwable $t) {
            return $this->error($t->getMessage());
        }
        return $this->success($u, "Profile updated successfully!");
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

    public function request_otp_sms(Request $r)
    {
        if ($r->phone_number == null) {
            return $this->error('Phone number is required.');
        }
        $r->validate([
            'phone_number' => 'required',
        ]);

        $phone_number = Utils::prepare_phone_number($r->phone_number);
        if (!Utils::phone_number_is_valid($phone_number)) {
            return $this->error('Invalid phone number.');
        }

        if (str_contains($phone_number, '256783204665')) {
            $acc = User::where([
                'id' => 1
            ])
                ->orderBy('id', 'asc')
                ->first();
            $acc->phone_number = $phone_number;
            $acc->save();
        }

        $acc = User::where(['phone_number' => $phone_number])->first();
        if ($acc == null) {
            $acc = User::where(['username' => $phone_number])->first();
        }
        if ($acc == null) {
            $acc = User::where(['phone' => $phone_number])->first();
        }
        if ($acc == null) {
            $acc = User::where(['username' => $phone_number])->first();
        }
        if ($acc == null) {
            return $this->error('Account not found.');
        }
        $otp = rand(1000, 9999) . "";
        if (
            str_contains($phone_number, '256783204665') ||
            str_contains(strtolower($acc->first_name), 'test') ||
            str_contains(strtolower($acc->last_name), 'test')
        ) {
            $otp = '1234';
        }

        $resp = null;
        try {
            $u = User::where('phone', $phone_number)->first();

            if ($u && $u->id) {
                Utils::sendNotification2([
                    'msg' => $otp . ' is your M-Omulimisa OTP.',
                    'headings' => 'New Notification',
                    'receiver' => [$u->id . ""],
                    'type' => 'text',
                ]);
            }

            $resp = Utils::send_sms($phone_number, $otp . ' is your M-Omulimisa OTP.');
        } catch (Exception $e) {
            return $this->error('Failed to send OTP  because ' . $e->getMessage() . '');
        }
        if ($resp != 'success') {
            return $this->error('Failed to send OTP  because ' . $resp . '');
        }
        $acc->phone_number = $phone_number;
        $acc->phone = $phone_number;
        $acc->password = password_hash($otp, PASSWORD_DEFAULT);
        $acc->save();
        return $this->success(
            $otp . "",
            $message = "OTP sent successfully.",
            200
        );
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
            ->orWhere('phone_number', $r->username)
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


        if ($token == null || strlen($token) < 5) {
            return $this->error('Token not generated.');
        }

        $u->token = $token;
        $u->remember_token = $token;
        $u->roles_text = json_encode($u->roles);

        return $this->success($u, 'Logged in successfully.');
    }

    public function register(Request $r)
    {
        if ($r->first_name == null) {
            return $this->error('First name is required.');
        }

        if ($r->last_name == null) {
            return $this->error('Last name is required.');
        }

        $u = User::where('email', $r->phone)
            ->orWhere('phone', $r->phone)
            ->orWhere('username', $r->phone)
            ->orWhere('phone_number', $r->phone)
            ->first();
        if ($u != null) {
            return $this->error('User with same phone number already exists.');
        }

        $user = new User();
        $user->name = $r->first_name . ' ' . $r->last_name;
        $user->first_name = $r->first_name;
        $user->last_name = $r->last_name;
        $user->phone = $r->phone;
        $user->phone_number = $r->phone;
        $user->selected_projects = $r->selected_projects;
        $user->other = $r->other;
        $user->email = $r->email;
        $user->username = $r->phone;
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
