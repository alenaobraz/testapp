<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $manager = new Role();
        $manager->role = 'manager';
        $manager->name = 'Менеджер';
        $manager->save();

        $customer = new Role();
        $customer->role = 'customer';
        $customer->name = 'Клиент';
        $customer->save();
    }
}
