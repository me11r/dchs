<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 19.07.2018
 * Time: 21:07
 */
class BurntObjectSeeder extends Seeder
{
    public function run()
    {
        (new App\Dictionary\BurntObject)->truncate();
        $items = [
            'Жилой сектор',
            'Производственные здания',
            'Энергетические системы',
            'Торговые предприятия',
            'Учебные заведения',
            'Детские и дошкольные',
            'Культурно-массовые и зрелищные учреждения',
            'Лечебно-профилактические',
            'Административно-общественные',
            'Строительные',
            'Подвижной состав',
            'Транспорт',
            'Здания выше 70 м',
            'Объекты жизнеобеспечения',
            'Дачный домик',
            'Объекты общепита',
        ];

        foreach ($items as $item) {
            (new \App\Dictionary\BurntObject())
                ->fill(['name' => $item])
                ->save();
        }
    }
}