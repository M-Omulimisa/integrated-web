<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($m) {
            $u = User::find($m->supplier);
            if ($u != null) {
                if ($m->phone == null || (strtr($m->phone) < 5)) {
                    $m->phone = $u->phone;
                    $m->phone = Utils::prepare_phone_number($m->phone);
                    if ($m->phone == null || (strlen($m->phone) < 5)) {
                        $m->phone = $u->phone_number;
                    }
                }
            }
            $m->phone = Utils::prepare_phone_number($m->phone);
            return true;
        });

        //updating 
        self::updating(function ($m) {
            $u = User::find($m->supplier);
            if ($u != null) {
                if ($m->phone == null || (strlen($m->phone) < 5)) {
                    $m->phone = $u->phone;
                    $m->phone = Utils::prepare_phone_number($m->phone);
                    if ($m->phone == null || (strlen($m->phone) < 5)) {
                        $m->phone = $u->phone_number;
                    }
                }
            }
            $m->phone = Utils::prepare_phone_number($m->phone);
            return true;
        });

        self::deleting(function ($m) {
            try {
                $imgs = Image::where('parent_id', $m->id)->orwhere('product_id', $m->id)->get();
                foreach ($imgs as $img) {
                    $img->delete();
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    public function getRatesAttribute()
    {
        $imgs = Image::where('parent_id', $this->id)->orwhere('product_id', $this->id)->get();
        return json_encode($imgs);
    }

    //product has many images
    public function images()
    {
        return $this->hasMany(Image::class, 'parent_id', 'id');
    }

    //getter for feature_photo 
    public function getFeaturePhotoAttribute($src)
    {
        if (strpos($src, 'images/') === false) {
            return '/images/' . $src;
        }
        return $src;
    }


    protected $casts = [
        'data' => 'json',
    ];

    //getter for in_stock
    public function getInStockAttribute($value)
    {
        if ($value == 1 || $value == '0' || $value == '' || $value == null) {
            return 'Units';
        }
        return $value;
    }
}
