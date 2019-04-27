<?php

return array (
    '/' => [
        'tabs' => [
            'tab1' => [
                'title' => 'Сведения о чрезвычайных ситуациях',
                'button_refresh' => 'Обновить',
                'tables' => [
                    'table_calls_infos' => [
                        'title' => 'Информация по звонкам',
                    ],
                    'table_cards_infos' => [
                        'title' => 'Информация по карточкам',
                        'headers' => [
                            'real_101' => '101 Боевые',
                            'drill_101' => '101 Учебные',
                            'norms_psp_101' => '101 Нормативы ПСП',
                            'other_rides_101' => '101 Прочие выезда',
                        ],
                    ],
                    'table_services_infos' => [
                        'title' => 'Информация от служб',
                        'headers' => [
                            'some' => 'СОМЭ',
                            'kaz_hydro' => 'Казгидромет',
                            'kaz_mudflow' => 'Казселезащита',
                            'some_description' => [
                                'description' => 'Описание',
                                'date_almaty' => 'дата и время Алматинского времени',
                                'epicenter' => 'эпицентр',
                                'mpv' => 'магнитуда',
                                'deep' => 'глубина',
                                'information' => 'сведения об ощутимости',
                                'energy_class' => 'энергетический класс',
                                'coordinates' => 'координаты эпицентра',
                                'created_at' => 'Дата заполнения',
                            ],
                            'kaz_hydro_description' => [
                                'description' => 'Прогноз погоды'
                            ],
                            'kaz_mudflow_headers' => [
                                'river' => 'Река',
                                'information' => 'Информация',
                                'gauging_station' => 'Наименование гидропостов и их отметка',
                                'water_flow_rate' => 'Расход воды',
                                'critical_water_flow_rate' => 'Критический расход воды',
                                'turbidity_of_water' => 'Мутность воды',
                                'max_turbidity_of_water' => 'Максимальная мутность воды',
                                'air_temperature' => 'Температура воздуха',
                                'water_temperature' => 'Температура воды',
                                'precipitation' => 'Осадки',
                                'height_of_snow' => 'Высота снега',
                                'weather' => 'Погода',
                                'comment' => 'Комментарий',
                                'updated_at' => 'Дата и время',
                            ],
                        ],
                    ],
                ]
            ],
            'tab2' => [
                'title' => 'Статус',
                'tables' => [
                    'table_forces' => [
                        'title' => 'Учет сил и средств',
                        'button_print' => 'Печать',
                        'button_export_xls' => 'Сохранить в XLSX',
                        'headers' => [
                            'fd' => 'ПЧ',
                            'status' => 'Статус',
                            'status_headers' => [
                                'department' => 'Отделение',
                                'departures_count' => 'Кол-во выездов за сегодня',
                                'real_departures_count' => 'Выезда по тревоге',
                                'drill_departures_count' => 'Учения',
                                'other_departures_count' => 'Прочие',
                                'status' => 'Статус',
                                'status_in_fd' => 'в ПЧ',
                                'status_subheaders' => [
                                    'address' => 'Адрес',
                                    'fire_rank' => 'Ранг пожара',
                                    'out_time' => 'Время выезда',
                                    'arrive_time' => 'Время прибытия',
                                ],
                            ],
                        ],
                    ]
                ]
            ],
        ]
    ],
);
