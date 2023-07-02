<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

use App\Models\Settings\Type;
use App\Models\Users\Role;
use App\Models\Users\Permission;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

// use App\Models\Core\Auth\Permission;
// use App\Models\Core\Auth\Role;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    use Uuid;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$guardName  = 'web';        
        $appId      = Type::findByAlias('app')->id;
        $superAdmin = User::first();
        $now        = \Carbon\Carbon::now();

        $adminRole  = Role::first();
        Role::where('id', '!=', $adminRole->id)->delete();

        Role::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_FIELD,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_SUPPORT,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_ORG_ADMIN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [

                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_ORG_USER,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_MICROFIN_ADMIN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_MICROFIN_USER,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_EXTN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_AGENT,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_PARTN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_INSTR,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_STDT,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_TRADER,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_BUYER_ADMIN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Role::ROLE_DISTR_ADMIN,
                'type_id'       => $appId,
                'created_by'    => $superAdmin->id,
                'guard_name'    => $guardName, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

    }
}


