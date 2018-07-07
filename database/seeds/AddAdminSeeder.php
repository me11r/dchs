<?php

use Illuminate\Database\Seeder;

class AddAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();
        $user = \App\User::create([
            'name' => 'Администратор',
            'email' => 'admin@localhost',
            'password' => bcrypt('password')
        ]);

        $user->rights()->sync([1,2,3,4,5,6,7]);
    }
}
