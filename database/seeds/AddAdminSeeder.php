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
        \Schema::disableForeignKeyConstraints();
        \App\User::truncate();
        $user = \App\User::create([
            'name' => 'Администратор',
            'email' => 'admin@localhost',
            'password' => bcrypt('password8')
        ]);

        $rights = \App\Right::all(['id']);
        $rights = $rights->pluck('id');
        $user->rights()->sync($rights);
        \Schema::enableForeignKeyConstraints();
    }
}
