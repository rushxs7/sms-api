<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'default']);

        $faker = Faker::create();

        $user = User::create([
            'name' => 'Rushil Ramautar',
            'email' => 'rushil.ramautar@datasur.sr',
            'password' => Hash::make('datasur123'),
        ]);
        $user->assignRole('admin');

        if (env("APP_ENV") != "production") {
            for ($i=0; $i < 10; $i++) {
                $user = User::create([
                    'name' => $faker->name(),
                    'email' => $faker->email(),
                    'password' => Hash::make('test123'),
                ]);
                $user->assignRole('default');
            }
        }
    }
}
