<?php

use Illuminate\Database\Seeder;

class FireLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new App\Dictionary\FireLevel)->truncate();
        $levels = [
            '1', '1 бис', '2', '2 бис', '3'
        ];

        foreach ($levels as $level) {
            (new \App\Dictionary\FireLevel())
                ->fill(['name' => $level])
                ->save();
        }
    }
}
