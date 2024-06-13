<?php

namespace App\Models\Mail;

use App\Models\User;
use App\Models\Settings\District;
use Illuminate\Database\Eloquent\Model;

class SystemMailLog extends Model
{
    protected $table = "email_logs";
    protected $fillable = ['recipient','subject','status'];

}