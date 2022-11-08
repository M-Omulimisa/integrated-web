<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

/**
 * Class LocationRelationship.
 */
trait MicrofinanceRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function administrator()
    {
        $role = Role::whereName(Role::ROLE_MICROFIN_ADMIN)->first();            
        return User::where('microfinance_id', $this->id)->whereIn('id',function($query) use ($role){
            $query->select('model_id')->where('role_id', $role->id)->from('model_has_roles');
        })->first();
    }
}
