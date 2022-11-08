<?php

namespace App\Models\Traits\Relationships\Users;

use App\Models\Users\Permission;

/**
 * Class RoleRelationship.
 */
trait RoleRelationship
{ 
    /**
     * Check if a role has permissions in a gven group
     *
     * @var bool
     */
    public function hasPermissionInGroup($permission_group_name)
    {
        $permissions = Permission::where('group_name', $permission_group_name)->get();
        foreach ($permissions as $permission) {
            if ($this->hasPermissionTo($permission->name)) {
                return true;
            }
        }
       return false;
    }

    public function permissionOfGroup($permission_group_name)
    {
        return Permission::where('group_name', $permission_group_name)->get();
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id');
    }
}