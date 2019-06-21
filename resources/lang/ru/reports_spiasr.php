<?php

return array(
    '/reports/analytics-spiasr' => [
        'water_consumption' => [
            'tab_title' => 'Расход воды',
            'title' => 'Расход воды за :date_from по :date_to',
            'headers' => [
                'card_number' => '№ карточки',
                'date' => 'Дата',
                'liquidation_method_id_1' => 'Первым стволом (стволами от емкости автоцистерны)',
                'liquidation_method_id_2' => 'С установкой пож.автомобилей на водоисточники, ПГ',
                'liquidation_method_id_3' => 'От емкости нескольких автоцистерн (подвозом воды)',
                'liquidation_method_id_9' => 'Пенные стволы',
                'liquidation_method_id_4' => 'Подручными средствами',
                'liquidation_method_id_5' => 'До прибытия',
                'time' => 'Время тушения',
            ]
        ],
        'object_classification' => [
            'tab_title' => 'Отчет по ПТЗ и ПТУ',
            'header_title' => 'Классификация объектов :type',
            'table_title' => 'Классификация объектов',
            'title' => 'Классификация объектов за :year г.',
            'sub_title' => ':type за :year г.',
            'drill_type' => 'Тип учения',
            'headers' => [
                'ptz' => 'классификация объектов ПТЗ',
                'ptu' => 'классификация объектов ПТУ',
            ]
        ],
        'other_rides_period' => [
            'tab_title' => 'Общий свод по прочим выездам',
            'title' => 'Общий свод по прочим выездам за :date_from по :date_to',
            'where_to' => 'Куда',
            'headers' => [
            ]
        ],
        'drill_rides_report' => [
            'tab_title' => 'Общий свод по учениям и занятиям',
            'title' => 'Общий свод по учениям и занятиям за :date_from по :date_to',
            'where_to' => 'Куда',
            'checked_pg' => 'ПровПГ',
            'damaged_pg' => 'НеиспПГ',
            'checked_pv' => 'ПровПВ',
            'damaged_pv' => 'НеиспПВ',
            'headers' => [
            ]
        ],
        'forces_resources' => [
            'tab_title' => 'Учет выездов подразделений',
            'title' => 'Учет выездов подразделений за :date_from по :date_to',
            'real_rides' => 'Количество выездов по тревоге',
            'real_rides2' => 'Общее количество выездов по тревоге',
            'fires' => 'Пожары',
            'asr' => 'Проведение аварийно-спасательных работ',
            'false_nonreal' => 'Ложные/Бдительность населения',
            'siren' => 'Срабатывание сигнализации',
            'area' => 'Область',
            'non_fires' => 'Случаи горения, не подлежащие учету как пожары',
            'practical_rides' => 'Практические выезда (ПТЗ,ПТУ,ТСУ,РКШУ,Учения, ТДК)',
            'corrections' => 'Корректировки',
            'other_rides' => 'Прочие Выезда',
        ],
        'report1' => [
            'tab_title' => 'Отчет-1',
            'title' => 'Отчет-1',
            'result' => 'Результат выезда',
            'fire_object' => 'Объект горения',
            'city_area' => 'Район города',
            'time_onway' => 'Время следования',
            'time_liqv' => 'Время ликвидации',
            'start_process' => 'Отправить в обработку',
        ],
        'consolidated_report' => [
            'tab_title' => 'Сводный отчет',
            'title' => 'Сводный отчет за :date_from по :date_to',
            'name' => 'Наименование',
            'count' => 'Количество',
        ],
        'emergency_form_051' => [
            'tab_title' => 'Форма ЧС-051',
            'title' => 'Учет аварийно спасательных работ, проведенных ГУ «Служба пожаротушения и аварийно-спасательных работ» ДЧС г. Алматы за :date_from по :date_to',
            'real_rides' => 'Кол-во выездов по тревоге',
            'asr' => 'Из них на АСР',
            'false_calls' => 'Кол-во ложных выездов',
            'within_asr' => 'В ходе аварийно-спасательных работ',
            'staff_count' => 'Численность привлеченного л/c (человек)',
            'tech_count' => 'Кол-во привлеченной техники (единиц)',
            'vehicles_saved' => 'Кол-во освобожденных а/м призаносах',
            'people_saved' => 'Кол-во спасенных человек',
            'children_saved' => 'В том числе детей',
            'bodies_excavated' => 'Извлечено тел',
            'children_bodies_excavated' => 'В том числе детей',
            'medical_help_applied' => 'Оказана мед. помощь',
            'medical_help_applied_children' => 'В том числе детям',
            'evacuated' => 'Кол-во эвакуированных',
            'evacuated_children' => 'В том числе детей',
        ],
    ],
);