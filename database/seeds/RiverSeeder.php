<?php

class RiverSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => "Бассейн реки Киши Алматы",
                'gauging_stations' =>
                    [
                        'Оз. №6 -ледник М. Маметовой, (3600)',
                        'Оз. №1 -ледник Туюк-Су (3400)',
                        'Мынжылкы (3017)',
                        'Медеу (2000)',
                        'Дамба (1179)',
                    ]
            ],
            [
                'name' => "Бассейн реки Улкен Алматы",
                'gauging_stations' =>
                    [
                        'Озерная (2600)',
                        'БАО (2500)',
                        'Кумбель- устье (2250)',
                        'Водоприемник (Алмаарасан) (1200)',
                    ]
            ],
            [
                'name' => "Бассейн реки Каргалы",
                'gauging_stations' =>
                    [
                        'Каргалы Озеро №3(Т-38)',
                        'Каргалы верховье (3350)',
                        'Плотина Каргалы (1250)'
                    ]
            ],
            [
                'name' => "Бассейн реки Аксай",
                'gauging_stations' =>
                    [
                        'Аксай сред/течение (2500)',
                        'АксайАкжар(1450)',
                        'Аксай-слияние (1400'
                    ]
            ],
        ];

        foreach ($items as $item) {
            $river = \App\Models\River::firstOrCreate([
                'name' => $item['name']
            ]);
            foreach ($item['gauging_stations'] as $gauging_station) {
                \App\Models\GaugingStation::firstOrCreate([
                    'name' => $gauging_station,
                    'river_id' => $river->id,
                    ]);
            }
        }
    }
}