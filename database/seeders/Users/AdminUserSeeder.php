<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

/**
 * Class AdminUserSeeder.
 */
class AdminUserSeeder extends Seeder
{
    use DisableForeignKeys, Uuid;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $now = \Carbon\Carbon::now();

        $admin = User::query()->create([
            'id'        => $this->generateUuid(),
            'name'      => 'Admin User',
            'phone'     => '256775666852',
            'email'     => 'super@omulimisa.org',
            'password'  => '123456',
            'status'    => 'Active',
            'verified'  => true,
            'created_at'    => $now, 
            'updated_at'    => $now
        ]);

        $this->enableForeignKeys();
    }
}
