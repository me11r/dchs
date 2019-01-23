<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $operatiorRole = \App\Role::firstOrCreate([
            'name' => 'dispatcher_103',
            'title' => 'Диспетчер 103',
        ]);

        $operatiorUser = \App\User::firstOrCreate([
            'role_id' => $operatiorRole->id,
            'name' => 'Диспетчер 103',
            'email' => '103@localhost.local',
            'password' => bcrypt('password'),
        ]);
    }
}
