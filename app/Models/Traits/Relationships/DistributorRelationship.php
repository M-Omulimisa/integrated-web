<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;
use App\Models\Settings\Location;
use App\Models\Loans\DistributorEnterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Loans\DistributorAgroProduct;
use App\Models\Settings\EnterpriseType;

/**
 * Class LocationRelationship.
 */
trait DistributorRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function administrator()
    {
        $role = Role::whereName(Role::ROLE_DISTR_ADMIN)->first();            
        return User::where('distributor_id', $this->id)->whereIn('id',function($query) use ($role){
            $query->select('model_id')->where('role_id', $role->id)->from('model_has_roles');
        })->first();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function enterprises()
    {
        return $this->hasMany(DistributorEnterprise::class, 'distributor_id');
    }

    public function products()
    {
        return $this->hasMany(DistributorAgroProduct::class, 'distributor_id');
    }

    public function enterprise_distributor_variety($enterpriseId)
    {
        $distributor = $this->id;
        return EnterpriseVariety::whereEnterpriseId($enterpriseId)->whereIn('id',function($query) use ($distributor){
            $query->select('enterprise_variety_id')->where('distributor_id', $distributor)->from('distributor_enterprise_varieties');
        })->get();
    }

    public function variety_distributor_type($varietyId)
    {
        $distributor = $this->id;
        return EnterpriseType::whereEnterpriseVarietyId($varietyId)->whereIn('id',function($query) use ($distributor){
            $query->select('enterprise_type_id')->where('distributor_id', $distributor)->from('distributor_enterprise_types');
        })->get();
    }


}
