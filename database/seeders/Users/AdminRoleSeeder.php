<?php

namespace Database\Seeders\Users;

use App\Models\User;
use App\Models\Users\Role;
use App\Models\Settings\Type;

use Illuminate\Database\Seeder;
use Database\Seeders\Traits\DisableForeignKeys;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

/**
 * Class AdminRoleSeeder.
 * Creates the Super Admin user role
 */
class AdminRoleSeeder extends Seeder
{
    use DisableForeignKeys, Uuid;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();
        Role::query()->truncate();

        $superAdmin = User::first();
        $guardName = 'web';
        $now = \Carbon\Carbon::now();

        $roles = [
            [
                'id'            => $this->generateUuid(),
                'name'          => config('access.users.app_admin_role'),
                'is_admin'      => 1,
                'type_id'       => Type::findByAlias('app')->id,
                'created_by'    => $superAdmin->id,
                'is_default'    => 1,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ]
        ];

        Role::query()->insert($roles);

        $this->enableForeignKeys();
    }
}
