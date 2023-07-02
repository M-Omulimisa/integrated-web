<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningStudent extends BaseModel
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
        'added_by',
        'email'
    ]; 

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
    
    public function added_by()
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
        self::creating(function (ELearningStudent $model) {
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