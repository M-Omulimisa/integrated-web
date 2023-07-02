<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

use App\Models\Users\Permission;
use App\Models\Users\Role;
use Database\Seeders\Traits\DisableForeignKeys;

class AdminUserPermissionSeeder extends Seeder
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
        
        $role          = Role::first();
        $permissions   = Permission::pluck('name','id')->all();
	    $role->syncPermissions($permissions);

        $this->enableForeignKeys();
    }
}
