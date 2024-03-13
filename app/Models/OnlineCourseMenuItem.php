<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseMenuItem extends Model
{
    use HasFactory;

    //fillable
    protected $fillable = [
        'audio',
        'language_id',
    ];

    //belongs to
    public function language()
    {
        return $this->belongsTo(\App\Models\Settings\Language::class);
    }

    public function onlineCourseMenu()
    {
        return $this->belongsTo(OnlineCourseMenu::class);
    }
}
