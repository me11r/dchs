<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 13:42
 */
class FireObjectSeeder extends Seeder
{
    public function run()
    {
        (new App\Dictionary\FireObject)->truncate();
        $list = [
            'Короткое замыкание',
            'Мусор',
            'Сухостой',
            'Пища на газе',
            'Жилой дом(квартира)',
            'Надворные постройки',
            'Прочие',
            'АСР',
            'Загорание а/м в результате ДТП',
            'Загорание бесхозных зданий, транспортных средств',
            'Битумные работы',
            'Срабатывание ОПС',
            'Область',
        ];
        foreach ($list as $item) {
            (new \App\Dictionary\FireObject)->fill(['name' => $item])->save();
        }
    }
}