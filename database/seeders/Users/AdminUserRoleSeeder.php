<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;

/**
 * Class AdminUserRoleSeeder.
 */
class AdminUserRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $users = User::get();
        foreach ($users as $user) {
            $user->assignRole(config('access.users.app_admin_role'));
        }

        $this->enableForeignKeys();
    }
}
