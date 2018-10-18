<?php

namespace App\Services;


class DbInfoService
{
    public function run()
    {
        $db_helper = new DbHelper();
        $file_helper = new FileHelper();
        $db_info = $db_helper->info();
        $result = [];
        $result[0] = [
            'Таблица',
            'Поле',
            'Тип',
            'Может принимать null',
            'Ключ',
            'Значение по умолчанию',
            'Дополнительно',
            'Комментарий',
        ];
        foreach ($db_info as $table_name => $table) {
            foreach ($table as $field) {
                $result[] = [$table_name, $field->Field, $field->Type, $field->Null, $field->Key, $field->Default, $field->Extra];
            }
        }

        return $file_helper->saveCsv($result,'db_info.csv');


    }
}