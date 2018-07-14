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
                'title' => 'Объект возгорания',
                'table' => 'dict_fire_object',
                'model' => \App\Dictionary\FireObject::class
            ],
            [
                'title' => 'Район города',
                'table' => 'dict_city_area',
                'model' => \App\Dictionary\CityArea::class
            ],

        ];
        (new App\Dictionary)->truncate();
        foreach ($dicts as $dict) {
            (new App\Dictionary())->fill($dict)->save();
        }
        $this->call(FireObjectSeeder::class);
        $this->call(CityAreaSeeder::class);
    }
}