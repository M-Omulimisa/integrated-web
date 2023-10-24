<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GardenCoordicate extends Model
{
    use HasFactory;
    //belongs to garden
    public function garden()
    {
        return $this->belongsTo(GardenModel::class, 'garden_id', 'id');
    }
}
