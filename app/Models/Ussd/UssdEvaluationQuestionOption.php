<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ussd\UssdQuestionOption;

class UssdEvaluationQuestionOption extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    protected $keyType = 'string';

    public $incrementing = false;
    
    protected $fillable = [

            'evaluation_question_option', 'ussd_evaluation_question_id', 'position'

    ];
}
