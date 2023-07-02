<?php
namespace Database\Seeders;

// use Database\Seeders\App\PermissionChildAppSeeder;

use Database\Seeders\Settings\TypeSeeder;

use Database\Seeders\Users\PermissionSeeder;
use Database\Seeders\Users\AdminRoleSeeder;
use Database\Seeders\Users\AdminUserRoleSeeder;
use Database\Seeders\Users\AdminUserSeeder;
use Database\Seeders\Users\UserRoleSeeder;
use Database\Seeders\Users\AdminUserPermissionSeeder;

use Database\Seeders\RolePermissionSeeder;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();
        $this->disableForeignKeys();
        activity()->disableLogging();

        $this->call(TypeSeeder::class);

        $this->call(PermissionSeeder::class);

        /* Handle Admin user account, role and permissions*/
        $this->call(AdminUserSeeder::class);
        $this->call(AdminRoleSeeder::class);
        $this->call(AdminUserRoleSeeder::class);
        $this->call(AdminUserPermissionSeeder::class);

        $this->call(UserRoleSeeder::class);

        // seeders here        
        $this->call(CountrySeeder::class);
        $this->call(CountryModuleSeeder::class);
        $this->call(LocationSeeder::class); 
        $this->call(OrganisationSeeder::class); 
        $this->call(SettingsASeeder::class); 
        $this->call(SeasonSeeder::class);
        $this->call(FarmerSeeder::class);                                      

        $this->call(RolePermissionSeeder::class);       

        $this->enableForeignKeys();
        Model::reguard();
        
    }
}
