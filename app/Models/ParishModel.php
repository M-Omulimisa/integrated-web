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

    //get name_text
    protected $appends = ['name_text'];

    //get name_text
    public function getNameTextAttribute()
    {
        $subcounty = $this->subcounty;
        if ($subcounty != null) {
            return $this->attributes['name'] . " - " . $subcounty->name_text;
        }
        return $this->attributes['name'];
    }

    //select data
    public static function selectData($subcounty_id = null)
    {
        $parishes = ParishModel::orderBy('name', 'asc');
        if ($subcounty_id != null) {
            $parishes = $parishes->where('subcounty_id', $subcounty_id);
        }
        $parishes = $parishes->get();
        $data = [];
        foreach ($parishes as $parish) {
            $data[$parish->id] = $parish->name_text;
        }
        return $data;
    }
}
