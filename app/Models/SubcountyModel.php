<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcountyModel extends Model
{
    protected $table = "subcounty";

    public function county()
    {
        return $this->belongsTo(CountyModel::class, 'county_id');
    }
    public function district()
    {
        return $this->belongsTo(DistrictModel::class, 'district_id');
    }

    //appends name_text to the model
    protected $appends = ['name_text'];

    public function getNameTextAttribute()
    {
        $district = $this->district;
        if ($district != null) {
            return $this->attributes['name'] . " (" . $district->name . ")";
        }
        return $this->attributes['name'];
    }
}
