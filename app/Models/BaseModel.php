<?php

namespace App\Models;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BaseModel extends Model
{
    use LogsActivity;
    protected static $logOnlyDirty = true;

    public function createdRules()
    {
        return [
            //
        ];
    }

    public function updatedRules()
    {
        return $this->createdRules();
    }

    public function scopeFilters($query, FilterBuilder $filter)
    {
        return $filter->apply($query);
    }
}
