<?php

use Illuminate\Database\Seeder;

class RightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [

            ['title' => 'Доступ в систему'],    //1
            ['title' => 'Доступ к заявкам'],    //2
            ['title' => 'Администрирование'],   //3
            ['title' => 'Общие права'],         //4
            ['title' => 'Строевые записки'],    //5
            ['title' => 'Отчеты'],              //6
            ['title' => 'Ввод данных'],         //7
        ];

        foreach ($groups as $group) {
            \App\Rights\Group::firstOrCreate($group);
        }

        $rights = [
            ['right_group_id' => 1, 'title' => 'Разрешить входить в систему'],

            ['right_group_id' => 2, 'title' => 'Просмотр заявок'],
            ['right_group_id' => 2, 'title' => 'Назначение заявок'],
            ['right_group_id' => 2, 'title' => 'Создание заявок'],
            ['right_group_id' => 2, 'title' => 'Редактирование заявок'],
            ['right_group_id' => 2, 'title' => 'Удаление заявок'],

            ['right_group_id' => 3, 'title' => 'Управление пользователями системы'],
            ['right_group_id' => 3, 'title' => 'Редактирование справочников'],

            ['right_group_id' => 4, 'title' => 'Получение путевых листов ПЧ'],
            ['right_group_id' => 4, 'title' => 'Может изменять список гидрантов'],
            ['right_group_id' => 4, 'title' => 'Может смотреть суточный отчет'],

            ['title' => 'СП и АСР', 'right_group_id' => 5],
            ['title' => 'РОСО', 'right_group_id' => 5],
            ['title' => 'ЦМК', 'right_group_id' => 5],
            ['title' => 'ГУ "Казселезащита"', 'right_group_id' => 5],
            ['title' => 'АО"Казавиаспас"', 'right_group_id' => 5],
            ['title' => 'АО "Өртсөндіруші"', 'right_group_id' => 5],
            ['title' => 'ДЧС г.Алматы', 'right_group_id' => 5],
            ['title' => 'Служба спасения г.Алматы', 'right_group_id' => 5],

            ['title' => 'Может одобрять суточный отчет', 'right_group_id' => 5],

            ['right_group_id' => 6, 'title' => 'Может смотреть информацию'],
            ['right_group_id' => 6, 'title' => 'Может смотреть оперативную информацию'],
            ['right_group_id' => 6, 'title' => 'Может смотреть отчет по ЛС'],
            ['right_group_id' => 6, 'title' => 'Может смотреть отчет по технике'],

            ['right_group_id' => 7, 'title' => 'Ручной ввод хронометража'],
            ['right_group_id' => 7, 'title' => 'Расположение гидрантов'],
            ['right_group_id' => 7, 'title' => 'Транспортные средства'],
            ['right_group_id' => 7, 'title' => 'Личный состав'],
            ['right_group_id' => 7, 'title' => 'Пожарные части'],
            ['right_group_id' => 7, 'title' => 'Моренные озера'],

            ['title' => 'Строевые: только чтение', 'right_group_id' => 5],

            ['right_group_id' => 4, 'title' => 'Получение путевых листов служб взаимодействия'],

            ['right_group_id' => 2, 'title' => 'Просмотр заявок 112'],
            ['right_group_id' => 2, 'title' => 'Назначение заявок 112'],
            ['right_group_id' => 2, 'title' => 'Создание заявок 112'],
            ['right_group_id' => 2, 'title' => 'Редактирование заявок 112'],
            ['right_group_id' => 2, 'title' => 'Удаление заявок 112'],

        ];

        foreach ($rights as $item) {
            \App\Right::firstOrCreate($item);
        }
    }
}
