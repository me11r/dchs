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

        ];
        (new App\Dictionary)->truncate();
        foreach ($dicts as $dict) {
            (new App\Dictionary())->fill($dict)->save();
        }
        $this->call(FireObjectSeeder::class);
        $this->call(CityAreaSeeder::class);
        $this->call(FireDeptSeeder::class);
        $this->call(FireLevelSeeder::class);
    }
}