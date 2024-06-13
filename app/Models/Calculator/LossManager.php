<?php

namespace App\Models\Calculator;

use App\Models\Settings\District;
use App\Models\Settings\Seasons;
use Illuminate\Database\Eloquent\Model;

class LossManager extends Model
{
    protected $table = "percentage_loss";
    protected $fillable = ['district_id', 'season_id', 'location', 're_average', 're_historical', 're_difference', 'loss'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function season()
    {
        return $this->belongsTo(Seasons::class);
    }
}