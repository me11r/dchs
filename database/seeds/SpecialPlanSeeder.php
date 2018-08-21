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
            ],
            [
                'fire_level_id' => 0,
                'city_area_id' => 0,
                'object_name' => 'Абылай хана, 62',
                'fire_department_id' => 0,
                'operational_plan_id' => 0,
                'location' => 'Абылай хана, 62'
            ],
            [
                'fire_level_id' => 0,
                'city_area_id' => 0,
                'object_name' => 'пр.Достык,56',
                'fire_department_id' => 0,
                'operational_plan_id' => 0,
                'location' => 'пр.Достык,56'
            ],
        ];

        foreach ($list as $item) {
            \App\Models\SpecialPlan::firstOrCreate($item);
        }
    }
}
