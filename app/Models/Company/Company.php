<?php

namespace App\Models\Company;

use App\Models\User;
use App\Models\Settings\District;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = "companys";
    protected $fillable = ['user_id', 'district', 'subcounty', 'parish'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}