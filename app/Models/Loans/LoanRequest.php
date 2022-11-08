<?php

namespace App\Models\Loans;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\CountryRelationship;
  
class LoanRequest extends BaseModel
{
    use Uuid, CountryRelationship;
  
    protected $fillable = [
        'project_id',
            'farmer_group_id',
            'microfinance_id',
            'type', ['Loan', 'Cash']);
            'user_id',
            'village_agent_id',
            'farmer_id',

            'approved_at',
            'rejected_at',
            'notes',
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
        self::creating(function (LoanRequest $model) {
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
