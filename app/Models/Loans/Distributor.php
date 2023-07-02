<?php

namespace App\Models\Loans;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\DistributorRelationship;
  
class Distributor extends BaseModel
{
    use Uuid, DistributorRelationship;
  
    protected $fillable = [
        'logo',
        'distributor_name',
        'contact_person_name',
        'contact_person_phone',
        'location_id',
        'address',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'bank_account_name'
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
        self::creating(function (Distributor $model) {
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
