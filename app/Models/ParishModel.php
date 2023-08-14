<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParishModel extends Model
{
    protected $table = "parish";
    public function subcounty(){
        return $this->belongsTo(SubcountyModel::class,'subcounty_id');
    }
}
