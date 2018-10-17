<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:42
 */

class DictionarySeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $dicts = [
            [
                'title' => 'Пожарные части',
                'table' => 'fire_deptartments',
                'model' => \App\FireDepartment::class,
            ],
            [
                'title' => 'Объект возгорания',
                'table' => 'dict_fire_object',
                'model' => \App\Dictionary\FireObject::class
            ],
            [
                'title' => 'Район города',
                'table' => 'dict_city_area',
                'model' => \App\Dictionary\CityArea::class
            ],
            [
                'title' => 'Класс пожара',
                'table' => 'dict_fire_level',
                'model' => \App\Dictionary\FireLevel::class
            ],
            [
                'title' => 'Способ ликвидации',
                'table' => 'dict_liquidation_method',
                'model' => \App\Dictionary\LiquidationMethod::class
            ],
            [
                'title' => 'Объект горения',
                'table' => 'dict_burn_object',
                'model' => \App\Dictionary\BurntObject::class
            ],
            [
                'title' => 'Причина выезда',
                'table' => 'dict_trip_result',
                'model' => \App\Dictionary\TripResult::class
            ],
            [
                'title' => 'Типы служб',
                'table' => 'service_types',
                'model' => \App\Models\ServiceType::class
            ],
            [
                'title' => 'Стволы',
                'table' => 'dict_trunk',
                'model' => \App\Models\Trunk::class
            ],
            [
                'title' => 'Источник противопожарного водоснабжения',
                'table' => 'water_supply_sources',
                'model' => \App\Dictionary\WaterSupplySource::class
            ],
        ];
        (new App\Dictionary)->truncate();
        foreach ($dicts as $dict) {
            (new App\Dictionary())->fill($dict)->save();
        }
        Schema::disableForeignKeyConstraints();
        $this->call(WaterSupplySeeder::class);
        $this->call(FireObjectSeeder::class);
        $this->call(CityAreaSeeder::class);
        $this->call(FireDeptSeeder::class);
        $this->call(FireLevelSeeder::class);
        $this->call(LiqvidationMethodSeeder::class);
        $this->call(BurntObjectSeeder::class);
        $this->call(TripResultSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(IncidentTypeSeeder::class);
        $this->call(OperationalPlanSeeder::class);
        $this->call(RiverSeeder::class);
        $this->call(TrunkSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
