<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;
    //boot method
    protected static function boot()
    {
        parent::boot();

        //deleting
        static::deleting(function ($crop) {
            throw new \Exception('You can not delete this crop'); 
        });
    }
}
