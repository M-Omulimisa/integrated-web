<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcountyModel extends Model
{
    protected $table = "subcounty";

    public function county(){
        return $this->belongsTo(CountyModel::class,'county_id');
    }
    public function district(){
        return $this->belongsTo(DistrictModel::class,'district_id');
    }
}
