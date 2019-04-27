<?php

return array(
    '/reports/analytics101' => [
        'tabs' => [
            'report_other_rides_period' => [
                'tab_title' => 'Общий свод по прочим выездам',
                'title' => 'Общий свод по прочим выездам за :date_from по :date_to',
            ],
            'report_drill_rides_period' => [
                'tab_title' => 'Общий свод по учениям и занятиям',
                'title' => 'Общий свод по учениям и занятиям за :date_from по :date_to',
            ],
            'report_forces_resources' => [
                'tab_title' => 'Учет выездов подразделений',
                'title' => 'Учет выездов подразделений за :date_from по :date_to',
                'headers' => [
                    'real_rides_count' => 'Количество выездов по тревоге',
                    'whole_real_rides_count' => 'Общее количество выездов по тревоге',
                    'fires' => 'Пожары',
                    'asr' => 'Проведение аварийно-спасательных работ',
                    'false_calls' => 'Ложные/Бдительность населения',
                    'signaling' => 'Срабатывание сигнализации',
                    'area' => 'Область',
                    'not_fires' => 'Случаи горения, не подлежащие учету как пожары',
                    'practical' => 'Практические выезда (ПТЗ,ПТУ,ТСУ,РКШУ,Учения, ТДК)',
                    'corrections' => 'Корректировки',
                    'other' => 'Прочие Выезда',
                ],
            ],
            'report_051' => [
                'real_rides_count' => 'Кол-во выездов по тревоге',
                'asr' => 'Из них на АСР',
                'false_calls' => 'Кол-во ложных выездов',
                'within_asr' => 'В ходе аварийно-спасательных работ',
                'staff_count' => 'Численность привлеченного л/c (человек)',
                'tech_count' => 'Кол-во привлеченной техники (единиц)',
                'tech_saved' => 'Кол-во освобожденных а/м призаносах',
                'people_saved' => 'Кол-во спасенных человек',
                'children_saved' => 'В том числе детей',
                'bodies_extracted' => 'Извлечено тел',
                'children_bodies_extracted' => 'В том числе детей',
                'medical_provided' => 'Оказана мед. помощь',
                'medical_provided_children' => 'В том числе детям',
                'evacuated' => 'Кол-во эвакуированных',
                'evacuated_children' => 'В том числе детей',
            ],
        ],
    ],
);