<?php

use Illuminate\Database\Seeder;

class StagingDeploySeeder extends Seeder
{
    public function run()
    {
        //$this->call(ChunkedBuildingsSeeder::class);

        \App\FireDepartment::firstOrCreate([
            'title' => 'ОД',
        ]);

        $this->call(IncidentTypeSeeder::class);
        $this->call(IncidentTypeCategoriesSeeder::class);
        $this->call(AircraftTypeSeeder::class);
        $this->call(AircraftSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(DictionarySeeder::class);
    }
}
