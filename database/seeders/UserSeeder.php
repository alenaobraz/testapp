<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $manager = Role::where('role',Config::get('constants.roles.manager'))->first();

        $user1 = new User();
        $user1->name = 'Образцова Алена';
        $user1->email = 'enjoy.obraz@gmail.com';
        $user1->password = bcrypt('123qweasd');
        $user1->save();
        $user1->roles()->attach($manager);
    }
}
