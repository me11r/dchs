<?php

use Illuminate\Database\Seeder;

class FormationReportsRights extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Rights\Group::insert([
            ['id' => 5, 'title' => 'Строевые записки'],
        ]);

        \App\Right::insert([
            ['id' => 12, 'title' => 'СП и АСР', 'right_group_id' => 5],
            ['id' => 13, 'title' => 'РОСО', 'right_group_id' => 5],
            ['id' => 14, 'title' => 'ЦМК', 'right_group_id' => 5],
            ['id' => 15, 'title' => 'ГУ "Казселезащита"', 'right_group_id' => 5],
            ['id' => 16, 'title' => 'АО"Казавиаспас"', 'right_group_id' => 5],
            ['id' => 17, 'title' => 'АО "Өртсөндіруші"', 'right_group_id' => 5],
        ]);
    }
}
