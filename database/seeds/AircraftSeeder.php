<?php

use Illuminate\Database\Seeder;

class AircraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'КА-32', 'aircraft_type_id' => 1],
            ['name' => 'МИ-8','aircraft_type_id' => 1],
            ['name' => 'МИ-171','aircraft_type_id' => 1],
            ['name' => 'ЕС-145','aircraft_type_id' => 1],
            ['name' => 'МИ-26','aircraft_type_id' => 1],
        ];

        foreach ($items as $item) {
            \App\Aircraft::firstOrcreate($item);
        }
    }
}
