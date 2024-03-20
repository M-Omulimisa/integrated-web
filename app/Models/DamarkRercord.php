<?php

namespace App\Models;

use App\Models\Farmers\Farmer;
use App\Models\Settings\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DamarkRercord extends Model
{
    use HasFactory;
    //boot
    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            self::process($model);
        });
        //updated
        static::updated(function ($model) {
            self::process($model);
        });
    }

    /* 
    
                $table->string('sender')->nullable();
            $table->text('message_body')->nullable();
            $table->text('external_ref')->nullable();
            $table->text('post_data')->nullable();
            $table->text('get_data')->nullable();
            $table->string('is_processed')->nullable()->default('No');
            $table->string('status')->nullable()->default('Pending');
            $table->text('error_message')->nullable();
            $table->string('type')->nullable();
            $table->string('farmer_id')->nullable();
            $table->string('question_id')->nullable();

    */

    //public static function process()
    public static function process($m)
    {
        if ($m->is_processed != 'No') {
            return;
        }
        $set = ' is_processed = "Yes" ';
        $phone_number = Utils::prepare_phone_number($m->sender);

        if (!Utils::phone_number_is_valid($phone_number)) {
            $set .= ', status = "Failed" ';
            $set .= ', error_message = "Invalid phone number ' . $phone_number . '" ';
            $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
            DB::update($sql);
            return;
        }

        //farmers


        $isQuestion = true;
        $keyword_1 = "";
        $keyword_2 = "";
        $keyword_3 = "";
        $keyword_4 = "";
        $keyword_5 = "";
        $keyword_6 = "";

        //replace double spaces with single space
        $m->message_body = preg_replace('/\s+/', ' ', $m->message_body);
        $words = explode(" ", $m->message_body);

        if (isset($words[0])) {
            $keyword_1 = trim($words[0]);
            $keyword_1 = strtolower($keyword_1);
        }

        if (isset($words[1])) {
            $keyword_2 = trim($words[1]);
        }

        if (isset($words[2])) {
            $keyword_3 = trim($words[2]);
        }

        if (isset($words[3])) {
            $keyword_4 = trim($words[3]);
        }

        if (isset($words[4])) {
            $keyword_5 = trim($words[4]);
        }
        if (isset($words[5])) {
            $keyword_6 = trim($words[5]);
        }



        $language = Language::where('sms_registration_keyword', $keyword_1)->first();

        if ($language != null) {
            $set .= ', type = "Registration" ';
            $isQuestion = false;
        } else {
            $set .= ', type = "other" ';
            $isQuestion = true;
        }

        if ($isQuestion) {
            $user = User::where('phone_number', $phone_number)->first();
            if ($user == null) {
                $user = User::where('phone', $phone_number)->first();
            }
            if ($user == null) {
                $farmer = Farmer::where('phone_number', $phone_number)->first();
                if ($farmer == null) {
                    $farmer = Farmer::where('phone', $phone_number)->first();
                }
                if ($farmer != null) {
                    try {
                        Farmer::process($farmer);
                    } catch (\Throwable $th) {
                    }
                }
                $user = User::where('phone_number', $phone_number)->first();
                if ($user == null) {
                    $user = User::where('phone', $phone_number)->first();
                }
            }

            if ($user == null) {
                $set .= ', status = "Failed", error_message = "User not found" ';
                $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
                DB::update($sql);
                return;
            }

            $language = Language::where('sms_keyword', $keyword_1)->first();
            $question = new FarmerQuestion();
            $question->user_id = $user->id;
            $question->language_id = $user->language_id;
            $question->district_model_id = $user->district_id;
            $question->subcounty_id = $user->subcounty_id;
            $question->body = $m->message_body;
            $question->body = str_replace($keyword_1, '', $question->body);
            $question->body = trim($question->body); //remove leading and trailing spaces
            $question->phone = $phone_number;
            $question->category = 'SMS';
            $question->sent_via = 'SMS';
            $question->answered = 'No';
            $question->answer_body = '';
            $question->video = '';
            $question->photo = '';
            $question->audio = '';
            $question->document = '';
            $question->views = 0;
            if ($language != null) {
                $set .= ', type = "Question" ';
                $question->language_id = $language->id;
            }
            try {
                $question->save();
                $set .= ', status = "Success" ';
                $set .= ', question_id = ' . $question->id . " ";
            } catch (\Throwable $th) {
                $set .= ', status = "Failed", error_message = "Unable to save question" ';
                $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
                DB::update($sql);
                return;
            }
        }

        if (!$isQuestion) {


            $famer = Farmer::where('phone_number', $phone_number)->first();
            if ($famer == null) {
                $famer = Farmer::where('phone', $phone_number)->first();
            }
            if ($famer != null) {
                //error farmer already exists
                $set .= ', status = "Failed", error_message = "Farmer already exists" ';
                $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
                DB::update($sql);
                return;
            }
            $farmer = new Farmer();
            $farmer->organisation_id = 1;
            $farmer->farmer_group_id = 1;
            $farmer->country_id = 1;
            $farmer->first_name = $keyword_4;
            $farmer->last_name = $keyword_5;

            if (strlen($keyword_5) > 2) {
                $farmer->last_name .= " " . $keyword_6;
            }

            $farmer->language_id = $language->id;
            $farmer->phone = $phone_number;
            $farmer->email = $phone_number;

            $dis = DistrictModel::where('name', $keyword_2)->first();
            if ($dis != null) {
                $farmer->district_id = $dis->id;
                $sub = SubcountyModel::where('name', $keyword_3)
                    ->where('district_id', $farmer->district_id)
                    ->first();
                if ($sub != null) {
                    $farmer->subcounty_id = $sub->id;
                }
            }

            try {
                $farmer->save();
                $set .= ', status = "Success" ';
                $set .= ', farmer_id = ' . $farmer->id . " ";
            } catch (\Throwable $th) {
                $set .= ', status = "Failed", error_message = "Unable to save farmer because ' . $th->getMessage() . '" ';
                $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
                DB::update($sql);
                return;
            }
        }
        $sql = "UPDATE damark_rercords SET $set WHERE id = $m->id";
        DB::update($sql);
    }
}
//[Mulimisa, Muhingisa] registation
//Mulimisa Kasese Bwera Muhindo Mubarak

//['other'], question  - sms_keyword
//manya what is the price of maize in Kampala?