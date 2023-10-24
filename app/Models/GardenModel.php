<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GardenModel extends Model
{
    use HasFactory;

    //coordinates
    public function coordinates()
    {
        return $this->hasMany(GardenCoordicate::class, 'garden_id', 'id');
    }

    public function setPhotosAttribute($photos)
    {
        if (is_array($photos)) {
            $this->attributes['photos'] = json_encode($photos);
        }
    }
    public function getPhotosAttribute($photos)
    {
        if ($photos != null) {
            return json_decode($photos, true);
        }
    }
}
