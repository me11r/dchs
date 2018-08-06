<?php

use Illuminate\Database\Seeder;

class SpecialPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            [
                'fire_level_id' => 3,
                'city_area_id' => 1,
                'object_name' => 'ПОЛИКЛИНИКА № 14',
                'fire_department_id' => 5,
                'operational_plan_id' => 1,
                'location' => 'ул.Ленина, 24'
            ],
            [
                'fire_level_id' => 3,
                'city_area_id' => 1,
                'object_name' => 'ТРОЛЛЕЙБУСНЫЙ  ПАРК № 3',
                'fire_department_id' => 5,
                'operational_plan_id' => 2,
                'location' => 'ул. Калининградская, 45'
            ],
            [
                'fire_level_id' => 3,
                'city_area_id' => 1,
                'object_name' => 'ТОО НПО «ДОРТЕХНИКА»',
                'fire_department_id' => 5,
                'operational_plan_id' => 3,
                'location' => 'ул. Емцова, 9 «Б»'
            ]
        ];

        foreach ($list as $item) {
            \App\Models\SpecialPlan::firstOrCreate($item);
        }
    }
}
