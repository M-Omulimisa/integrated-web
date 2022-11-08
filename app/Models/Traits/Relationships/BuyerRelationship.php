<?php

namespace App\Models\Traits\Relationships;

use App\Models\Loans\BuyerEnterprise;
use App\Models\Settings\Location;
use App\Models\Users\Role;
use App\Models\User;

/**
 * Class LocationRelationship.
 */
trait BuyerRelationship
{
    public function enterprises()
    {
        return $this->hasMany(BuyerEnterprise::class, 'buyer_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function administrator()
    {
        $role = Role::whereName(Role::ROLE_BUYER_ADMIN)->first();            
        return User::where('buyer_id', $this->id)->whereIn('id',function($query) use ($role){
            $query->select('model_id')->where('role_id', $role->id)->from('model_has_roles');
        })->first();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
