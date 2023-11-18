<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ussd\UssdQuestionOption;

class UssdAdvisoryQuestion extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    protected $keyType = 'string';

    public $incrementing = false;
    
    protected $fillable = [

            'question', 'description','position','ussd_advisory_topic_id'

    ];

    public function options()
    {
        return $this->hasMany(UssdQuestionOption::class, 'ussd_advisory_question_id', 'id');
    }
}

