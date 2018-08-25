<?php

use Illuminate\Database\Seeder;

class WaterSupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'Уличный ПГ',
            'Объектовый ПГ',
            'Объектовый ПВ',
            'Открытый водоисточник',
        ];

        foreach ($items as $item) {
            $new = [
                'name' => $item,
            ];
             \App\Dictionary\WaterSupplySource::updateOrCreate($new, $new);
        }
    }
}
