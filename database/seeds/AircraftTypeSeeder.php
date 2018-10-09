<?php

use Illuminate\Database\Seeder;

class AircraftTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Основная',],
            ['name' => 'Специальная',],
            ['name' => 'Вспомогательная',],
            ['name' => 'Другая',],
        ];

        foreach ($items as $item) {
            \App\AircraftType::firstOrCreate($item);
        }
    }
}
