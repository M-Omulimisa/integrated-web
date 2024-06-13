<?php

namespace App\Models\Calculator;

use App\Models\Settings\District;
use Illuminate\Database\Eloquent\Model;

class DistrictInsured extends Model
{
    protected $table = "districts_insured";
    protected $fillable = ['district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}