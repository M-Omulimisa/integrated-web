<?php

namespace App\Models\Calculator;

use App\Models\Settings\Item;
use Illuminate\Database\Eloquent\Model;

class ItemLimit extends Model
{
    protected $table = "item_limits";
    protected $fillable = ['item_id','maximum_sum'];

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
}

