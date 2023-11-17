<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class UssdAdvisoryMessageOutbox extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    
    protected $fillable = [

        'session_id', 'status', 'message'

    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function (UssdAdvisoryMessageOutbox $model) {
            $model->id = $model->generateUuid();
        });
    }

    protected $keyType = 'string';

    public $incrementing = false;
}
