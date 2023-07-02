<?php

namespace App\Models\IdValidations;
  
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Facades\IdValidation\PhoneValidationServiceFacade;
use App\Models\User;
use App\Models\Organisations\Organisation;
  
class PhoneValidation extends BaseModel
{
    use Uuid;

    public const PEND       = "PENDING";
    public const NONE       = "NOT FOUND";
    public const FAIL       = "FAILED";
    public const SUCCESS    = "SUCCESSFUL";
  
    protected $fillable = [
        'reference',
        'user_id',
        'organisation_id',
        'phonenumber',
        'error_code',
        'error_message',
        'message_payload',
        'cost',
        'phone_status',
        'phone_match',
        'phone_surname',
        'phone_firstname',
        'phone_middlename',
        'mno_authority',
        'report_path',
        'source',
        'token',
        'status'
    ];

    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (PhoneValidation $model) {
            $model->id = $model->generateUuid();
            $model->reference   = PhoneValidationServiceFacade::generatePhoneReference();
            if (auth()->user()) {
                $model->user_id     = auth()->user()->id;
                $model->organisation_id = auth()->user()->organisation_id ?? null;
            }
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
