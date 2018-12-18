<?php

use Illuminate\Database\Seeder;

class DailyReportPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persons = [
            [
                'position' => 'Начальнику ГУ «СП и АСР»',
                'city' => 'ДЧС г. Алматы КЧС МВД РК',
                'post' => 'полковнику гражданской защиты',
                'name' => 'Касыбаеву Р.А.',
                'type' => 'header',
            ],
            [
                'position' => 'Ст. инженер ЦППС ЦОУСС',
                'city' => 'ГУ «СП и АСР ДЧС г. Алматы КЧС МВД РК»',
                'post' => 'майор гражданской защиты',
                'name' => 'Серик И. С.',
                'type' => 'footer_first',
            ],
            [
                'position' => 'Оперативный дежурный ДСПТ',
                'city' => 'ГУ «СП и АСР ДЧС г. Алматы КЧС МВД РК»',
                'post' => 'капитан гражданской защиты',
                'name' => 'Ганиев П.М.',
                'type' => 'footer_second',
            ],
        ];

        foreach ($persons as $person) {
            $model = \App\Models\DailyReportPerson::where('type', '=', $person['type'])->first();
            if(!$model) {
                \App\Models\DailyReportPerson::create($person);
            }
        }
    }
}
