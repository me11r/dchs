<?php

use Illuminate\Database\Seeder;

class StagingDeploySeeder extends Seeder
{
    public function run()
    {
        $this->call(BurntObjectsSeeder::class);
        //$this->call(ChunkedBuildingsSeeder::class);
    }
}
