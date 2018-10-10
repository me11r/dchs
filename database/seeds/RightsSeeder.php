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
        \App\Rights\Group::truncate();

        \App\Rights\Group::insert([
            ['title' => 'Доступ в систему'],
            ['title' => 'Доступ к заявкам'],
            ['title' => 'Администрирование'],
            ['title' => 'Общие права'],
            ['title' => 'Строевые записки'],
            ['title' => 'Отчеты'],
            ['title' => 'Ввод данных'],


        ]);

        \App\Right::truncate();
        \App\Right::insert([
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


        ]);
    }
}
