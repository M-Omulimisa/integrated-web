<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPhoneNumber extends Model
{
    use HasFactory;

    //boot creating
    protected static function boot()
    {
        parent::boot();
        self::creating(function (TestPhoneNumber $model) {
            $phone = Utils::prepare_phone_number($model->phone);
            $model->phone = $phone;
            if (!Utils::phone_number_is_valid($phone)) {
                throw new \Exception('Invalid phone number');
            }
        });
        //updating
        self::updating(function (TestPhoneNumber $model) {
            $phone = Utils::prepare_phone_number($model->phone);
            $model->phone = $phone;
            if (!Utils::phone_number_is_valid($phone)) {
                throw new \Exception('Invalid phone number');
            }
        });
    }
}
