<?php

namespace App\Models\Calculator;

use App\Models\Settings\Item;
use Illuminate\Database\Eloquent\Model;

class ItemInsured extends Model
{
    protected $table = "items_insured";
    protected $fillable = ['item_id'];

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
}