<?php

use Illuminate\Database\Seeder;

class StagingDeploySeeder extends Seeder
{
    public function run()
    {
        //$this->call(ChunkedBuildingsSeeder::class);
        $copyFrom = public_path('alarm.wav');
        $copyTo = public_path('assets/alarm.wav');
        if(file_exists($copyFrom) && !file_exists($copyTo)) {
            copy($copyFrom, $copyTo);
        }

        \App\FireDepartment::firstOrCreate([
            'title' => 'ОД',
        ]);

        $this->call(IncidentTypeSeeder::class);
        $this->call(IncidentTypeCategoriesSeeder::class);
        $this->call(AircraftTypeSeeder::class);
        $this->call(AircraftSeeder::class);
        $this->call(RightsSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(DictionarySeeder::class);
    }
}
