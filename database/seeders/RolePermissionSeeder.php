<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Users\Permission;
use App\Models\Users\Role;
use Database\Seeders\Traits\DisableForeignKeys;

class RolePermissionSeeder extends Seeder
{
	use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->disableForeignKeys();  

    	$role = Role::whereName(Role::ROLE_ADMIN)->first();
	    $role->revokePermissionTo([

	    ]);      
        
        $role = Role::whereName(Role::ROLE_FIELD)->first();
	    $role->syncPermissions([

	    ]);

        $this->enableForeignKeys();
    }
}