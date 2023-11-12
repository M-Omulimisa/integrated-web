<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParishModel extends Model
{
    protected $table = "parish";
    public function subcounty()
    {
        return $this->belongsTo(SubcountyModel::class, 'subcounty_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            $par = ParishModel::where([
                'subcounty_id' => $data->subcounty_id,
                'name' => $data->name
            ])->first();
            //check if the subcounty already exists
            if ($par != null) {
                return false;
            }
        });
    }
}
