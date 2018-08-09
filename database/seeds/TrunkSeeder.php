<?php

use Illuminate\Database\Seeder;

class TrunkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new App\Models\Trunk)->truncate();
        $items = [
            'А',
            'Б',
            'СРП-50А',
            'РСК-50',
            'РС-70',
            'Лафетных',
            'СВД',
            'ГПС-600',
            'СВП',
            'Пурга-2',
            'Пурга-5',
            'ТАВ',
            'Ермак',
            'ГИРС',
            'IFEX',
            'Аргамак',
        ];

        foreach ($items as $item) {
            (new \App\Models\Trunk)
                ->fill(['name' => $item])
                ->save();
        }
    }
}
