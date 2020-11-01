<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name','admin')->first();

        $admin = User::create([
                                  'name' => 'admin',
                                  'email' => 'oleg.vostruxin@yandex.ru',
                                  'password' => bcrypt('123456')
                              ]);

        $admin->roles()->attach($adminRole);
    }
}
