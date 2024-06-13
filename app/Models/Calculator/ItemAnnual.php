<?php

namespace App\Models\Calculator;

use App\Models\Settings\Item;
use Illuminate\Database\Eloquent\Model;

class ItemAnnual extends Model
{
    protected $table = "items_annual";
    protected $fillable = ['item_id'];

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
}