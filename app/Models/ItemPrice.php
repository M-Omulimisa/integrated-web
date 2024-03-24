<?php

namespace App\Models;

use App\Models\Settings\Enterprise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory;

    //item_id belongs to Enterprise
    public function item()
    {
        return $this->belongsTo(Enterprise::class, 'item_id');
    }

    //translate
    public static function latest_price($item_id)
    {
        $data = ItemPrice::where([
            'item_id' => $item_id
        ])
            ->orderBy('id', 'desc')
            ->first();
        if ($data == null) {
            return 0;
        }
        return $data->price;
    }
}
