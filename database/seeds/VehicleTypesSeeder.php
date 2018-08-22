<?php

use Illuminate\Database\Seeder;

class VehicleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Основная',
            'Специальная',
            'Вспомогательная',
        ];

        foreach ($types as $type) {
            \App\Models\VehicleType::create(['name' => $type]);
        }
    }
}
