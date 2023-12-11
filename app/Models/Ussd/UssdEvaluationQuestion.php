<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ussd\UssdEvaluationQuestionOption;

class UssdEvaluationQuestion extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    protected $keyType = 'string';

    public $incrementing = false;
    
    protected $fillable = [

            'evaluation_question', 'ussd_language_id', 'position'

    ];

    public function options()
    {
        return $this->hasMany(UssdEvaluationQuestionOption::class, 'ussd_evaluation_question_id', 'id');
    }
}
