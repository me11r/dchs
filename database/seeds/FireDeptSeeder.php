<?php


use Illuminate\Database\Seeder;

class FireDeptSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'ПЧ-1',
            'ПЧ-2',
            'ПЧ-3',
            'ПЧ-4',
            'ПЧ-5',
            'ПЧ-6',
            'СПЧ-7',
            'СПЧ-8',
            'СПЧ-9',
            'ПЧ-10',
            'СПЧ-11',
            'ПЧ-12',
            'ПЧ-13',
            'СПЧ-14',
            'СПЧ-15',
            'ПП-16',
            'ПП-17',
            'СО',
            'ПЧ-7',
            'ПЧ-17',
            'ПЧ-8'
        ];
        (new App\FireDepartment)->truncate();
        foreach ($departments as $department) {
            (new \App\FireDepartment())->fill(['title' => $department])->save();
        }
    }
}
