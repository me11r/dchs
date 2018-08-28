<?php

use Illuminate\Database\Seeder;

class StagingDeploySeeder extends Seeder
{
    public function run()
    {
        $this->call(SchedulesSeeder::class);
    }
}
