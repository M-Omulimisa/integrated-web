<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends BaseModel
{
    use Uuid;
    use HasFactory;
    use SoftDeletes; // Add this if you want soft deletes

    protected $fillable = [
        'name', 'menu_status'
    ];


    public function enterprises()
    {
        return $this->belongsToMany(Enterprise::class);
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casted = [
        'enterprises' => 'array',
    ];

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

    /**
     * Get the formatted created_at attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Get the formatted updated_at attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
