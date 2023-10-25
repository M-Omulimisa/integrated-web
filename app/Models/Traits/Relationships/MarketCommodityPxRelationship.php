<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Currency;
use App\Models\Market\MarketOutputProduct;
use App\Models\Market\Market;

/**
 * Class LocationRelationship.
 */
trait MarketCommodityPxRelationship
{
    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function commodity()
    {
        return $this->belongsTo(MarketOutputProduct::class, 'output_product_id');
    }
}
