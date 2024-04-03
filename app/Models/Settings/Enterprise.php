<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\EnterpriseRelationship;

class Enterprise extends BaseModel
{
    use Uuid, EnterpriseRelationship;

    protected $fillable = [
        'name', 'category', 'description', 'unit_id'
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
        self::creating(function (Enterprise $model) {
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

    //beloings MeasureUnit::find($value->unit_id);
    public function unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }
    //getter for name_text
    public function getNameTextAttribute($name)
    {
        return $this->name;
        $unit = MeasureUnit::find($this->unit_id);
        if ($unit != null) {
            return $this->name . ' (' . $unit->slug . ')';
        }
        return $this->name;
    }
}
