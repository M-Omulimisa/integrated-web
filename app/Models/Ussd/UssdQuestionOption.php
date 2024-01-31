<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ussd\UssdAdvisoryQuestion;

class UssdQuestionOption extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    protected $keyType = 'string';

    public $incrementing = false;
    
    protected $fillable = [

            'option', 'ussd_advisory_question_id', 'position'
            
        ];


    public function question(){

        return $this->belongsTo(UssdAdvisoryQuestion::class, 'ussd_advisory_question_id');

    }

}

