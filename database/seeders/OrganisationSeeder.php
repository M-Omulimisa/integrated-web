<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

use App\Models\User;
use App\Models\Users\Role;
use App\Models\Settings\Country;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationPermission;
use App\Models\Organisations\OrganisationPosition;
use App\Models\Organisations\OrganisationPositionPermission;
use App\Models\Organisations\OrganisationUserPosition;

class OrganisationSeeder extends Seeder
{
    use Uuid, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys();
        $now        = \Carbon\Carbon::now()->subMonths(6);

        $organisation = [
                'name' => 'Test Organisation',
                'address' => 'Kisaasi-Ntinda road, Kampala, Uganda',
                'services' => 'Extension Services, Input Loans'
            ];

            $organisation = Organisation::create($organisation);

            if ($organisation) {

                $country = Country::where('dialing_code', '256')->first();

                $user = [
                    'name' => 'Test Org Admin',
                    'email' => 'testadmin@organisation.com',
                    'phone' => '256701222222',
                    'organisation_id' => $organisation->id,
                    'password' => '123456', 
                    'status' => "Active",
                    'country_id' => $country->id,
                ];

                $user = User::create($user);
                $user->assignRole(Role::ROLE_ORG_ADMIN);
            }



            OrganisationPermission::insert([
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'manage_farmers',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'list_farmers',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'manage_farmer_groups',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'list_farmer_groups',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'manage_agents',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'list_agents',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'manage_questions',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'list_questions',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'manage_alerts',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id'  => $this->generateUuid(),
                    'name' => 'list_alerts',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
            ]);


            $position = OrganisationPosition::create([
                'name' => 'National Officer',
                'organisation_id' => $organisation->id, 
                // 'location_level'
            ]);

            $permissions = OrganisationPermission::all();

            foreach ($permissions as $permission) {
                OrganisationPositionPermission::create([
                    'position_id' => $position->id,
                    'permission_id' => $permission->id
                ]);
            }


            // Organisation User
            $user = [
                    'name' => 'Test Org User',
                    'email' => 'testuser@organisation.com',
                    'phone' => '256701333333',
                    'organisation_id' => $organisation->id,
                    'password' => '123456', 
                    'status' => "Active",
                    'country_id' => $country->id,
                ];

                $user = User::create($user);
                $user->assignRole(Role::ROLE_ORG_USER);

            OrganisationUserPosition::create([
                'position_id' => $position->id,
                'user_id' => $user->id
            ]);

        $this->enableForeignKeys();
    }


}
