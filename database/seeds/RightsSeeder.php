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
            ['id' => 1, 'title' => 'Доступ в систему'],
            ['id' => 2, 'title' => 'Доступ к заявкам'],
            ['id' => 3, 'title' => 'Администрирование'],
            ['id' => 4, 'title' => 'Общие права'],

        ]);

        \App\Right::truncate();
        \App\Right::insert([
            ['id' => 1, 'right_group_id' => 1, 'title' => 'Разрешить входить в систему'],

            ['id' => 2, 'right_group_id' => 2, 'title' => 'Просмотр заявок'],
            ['id' => 3, 'right_group_id' => 2, 'title' => 'Назначение заявок'],
            ['id' => 4, 'right_group_id' => 2, 'title' => 'Создание заявок'],
            ['id' => 5, 'right_group_id' => 2, 'title' => 'Редактирование заявок'],
            ['id' => 6, 'right_group_id' => 2, 'title' => 'Удаление заявок'],

            ['id' => 7, 'right_group_id' => 3, 'title' => 'Управление пользователями системы'],
            ['id' => 9, 'right_group_id' => 3, 'title' => 'Редактирование справочников'],
            ['id' => 8, 'right_group_id' => 4, 'title' => 'Получение путевых листов ПЧ'],
            ['id' => 10, 'right_group_id' => 4, 'title' => 'Может изменять список гидрантов'],
            ['id' => 11, 'right_group_id' => 4, 'title' => 'Может смотреть суточный отчет']
        ]);

        $this->call(FormationReportsRights::class);
    }
}
