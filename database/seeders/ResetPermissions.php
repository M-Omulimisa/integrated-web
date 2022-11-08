<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

use App\Models\Users\Permission;
use Database\Seeders\Users\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;

use App\Models\Users\Role;

class ResetPermissions extends Seeder
{
    use DisableForeignKeys, Uuid;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        /* Reseting Roles and Permissions */

        \DB::table('role_has_permissions')->truncate();
        Permission::query()->truncate();

        $this->call(PermissionSeeder::class);

        \Artisan::call('permission:cache-reset');

        $role          = Role::whereName(Role::ROLE_ADMIN)->first();
        $permissions   = Permission::pluck('name','id')->all();
        $role->syncPermissions($permissions);

        $this->call(RolePermissionSeeder::class);

        $this->enableForeignKeys();
    }
}

