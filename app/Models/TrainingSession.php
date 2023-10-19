<?php

namespace App\Models;

use App\Models\Farmers\Farmer;
use App\Models\Settings\Location;
use App\Models\Training\Training;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    function training()
    {
        return $this->belongsTo(Training::class);
    }
    function location()
    {
        return $this->belongsTo(Location::class);
    }
    function conducted()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }

    function members()
    {
        return $this->belongsToMany(Farmer::class, 'training_training_session_has_members', 'training_session_id', 'user_id'); 
    }

    public function setAttendanceListPicturesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['attendance_list_pictures'] = json_encode($pictures);
        }
    }
    public function getAttendanceListPicturesAttribute($pictures)
    {
        if ($pictures != null)
            return json_decode($pictures, true);
    }



    public function setMembersPicturesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['members_pictures'] = json_encode($pictures);
        }
    }
    public function getMembersPicturesAttribute($pictures)
    {
        if ($pictures != null)
            return json_decode($pictures, true);
    }
}
