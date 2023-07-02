<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningInstructor extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'full_name',
        'picture',
        'gender',
        'age_group',
        'affiliation',
        'qualification',
        'country',
        'phone_number',
        'email_notifications',
        'sms_notifications',
        'organisation_id',
        'district_id',
        'subcounty_id',
        'parish_id',
        'village',
        'business',            
        'user_id',
    ]; 

    public const gender         = [
        "Male" => "Male", 
        "Female" => "Female", 
        "Not Disclosed" => "Not Disclosed"
    ];
    public const affiliation    = [
        "None" => "None", 
        "Academia" => "Academia", 
        "Individual" => "Individual", 
        "Community Organisation" => "Community Organisation", 
        "For-Profit Organisation" => "For-Profit Organisation", 
        "Non-Profit Organisation" => "Non-Profit Organisation", 
        "Not Disclosed" => "Not Disclosed"
    ];
    public const age_group      = [
        "None" => "None", 
        "Less than 16" => "Less than 16", 
        "16-20" => "16-20", 
        "21-25" => "21-25", 
        "26-30" => "26-30", 
        "31-35" => "31-35", 
        "36-40" => "36-40", 
        "41-45" => "41-45", 
        "46-50" => "46-50", 
        "Greater than 50" => "Greater than 50", 
        "Not Disclosed" => "Not Disclosed"
    ];
    public const qualification  = [
        "None" => "None", 
        "Hi School" => "Hi School", 
        "Pre University" => "Pre University", 
        "Under Graduate" => "Under Graduate", 
        "Post Graduate" => "Post Graduate", 
        "Doctorate" => "Doctorate", 
        "Other" => "Other", 
        "Not Disclosed" => "Not Disclosed"
    ];
    public const countries      = ["Uganda" => "Uganda"];

    public function organisation()
    {
        return $this->belongsTo(User::class, 'organisation_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    } 
    
    public function subcounty()
    {
        return $this->belongsTo(Subcounty::class);
    } 
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (ELearningInstructor $model) {
            $model->id = $model->generateUuid();
        });
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}