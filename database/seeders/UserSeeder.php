<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::create(['name' => 'superadmin']);
        $orgAdmin = Role::create(['name' => 'orgadmin']);
        $default = Role::create(['name' => 'default']);

        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_organizations']);
        Permission::create(['name' => 'manage_organization_users']);
        Permission::create(['name' => 'manage_organization_sender']);
        Permission::create(['name' => 'view_jobs']);
        Permission::create(['name' => 'create_jobs']);
        Permission::create(['name' => 'cancel_jobs']);

        $superAdmin->givePermissionTo('manage_users');
        $superAdmin->givePermissionTo('manage_organizations');
        $superAdmin->givePermissionTo('view_jobs');
        $superAdmin->givePermissionTo('create_jobs');
        $superAdmin->givePermissionTo('cancel_jobs');

        $orgAdmin->givePermissionTo('manage_organization_users');
        $orgAdmin->givePermissionTo('manage_organization_sender');
        $orgAdmin->givePermissionTo('view_jobs');
        $orgAdmin->givePermissionTo('create_jobs');
        $orgAdmin->givePermissionTo('cancel_jobs');

        $datasurOrg = Organization::where('name', 'Datasur')->first();

        $faker = Faker::create();

        $user = User::create([
            'name' => 'Rushil Ramautar',
            'email' => 'rushil.ramautar@datasur.sr',
            'password' => Hash::make('datasur123'),
            'organization_id' => $datasurOrg->id,
        ]);
        $user->assignRole('superadmin');


        if (env("APP_ENV") != "production") {
            for ($i=0; $i < 10; $i++) {
                $user = User::create([
                    'name' => $faker->name(),
                    'email' => $faker->email(),
                    'password' => Hash::make('test123'),
                    'organization_id' => $datasurOrg->id,
                ]);
                $user->assignRole('default');
            }
        }
    }
}
