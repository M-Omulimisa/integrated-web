<?php

namespace App\Models\Ussd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ussd\UssdQuestionOption;

class UssdAdvisoryMessage extends Model
{
    use Uuid,SoftDeletes;

    protected $connection = 'mysql';

    protected $keyType = 'string';

    public $incrementing = false;
    
    protected $fillable = [

            'message', 'ussd_question_option_id'

    ];


    public function option(){

        return $this->belongsTo(UssdQuestionOption::class, 'ussd_question_option_id');

    }
}
