<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'title' => 'Администратор',
            ],
            [
                'name' => 'dispatcher_od',
                'title' => 'Диспетчер ОД',
            ],
            [
                'name' => 'dispatcher_pch',
                'title' => 'Диспетчер ПЧ',
            ],
            [
                'name' => 'dstp',
                'title' => 'ДСТП',
            ],
            [
                'name' => 'emergency_service',
                'title' => 'Служба взаимодействия',
            ],
            [
                'name' => 'analyst',
                'title' => 'Аналитик',
            ],
            [
                'name' => 'head',
                'title' => 'Руководство',
            ],
            [
                'name' => 'dispatcher_zouss',
                'title' => 'Диспетчер ЦОУСС',
            ],
        ];

        foreach ($roles as $role) {
            \App\Role::firstOrCreate($role);
        }

        $rights = \App\Right::all();

        $user = \App\User::find(1);
        $user->role_id = \App\Role::name('admin')->first()->id;
        $user->save();

        $rights_arr = $rights->pluck('id')->toArray();

        $user->role->rights()->sync($rights_arr);
    }
}
