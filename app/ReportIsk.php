<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportIsk extends Model
{
    protected $fillable = [
        'ticket101_id',
        'ticket_type',//Номер и вид карточки (дата): основная (первая), дополнительная
        'emergency_types',//Виды ЧС
        'distance_emergency_fd',//Расстояние от места ЧС до ближайшей пожарной части, (км)
        'distance_emergency_city',//Расстояние от места возникновения ЧС до близлежащего населенного пункта
        'wind_speed_direction',//Скорость и направление ветра
        'fire_speed',//Скорость распространения огня м/с
        'circs_emergency_escalate',//Условия, способствовавшие развитию ЧС
        'material_damage',//Нанесенный материальный ущерб (тыс. тенге), всего
        'animals_died',//Погибло животных (птиц), всего
        'animals_saved',//Количество спасенных животных (птиц)
        'destroyed',//Уничтожено, всего
        'damaged',//Повреждено, всего
        'wealth_saved',//Спасено материальных ценностей (тыс. тенге), всего
        'destruction_description',//Наличие и характер разрушений, обрушений, повреждений (описать)
        'liquidation_evolved',//Задействовано в ликвидации, всего:
        'gsgz',//Уполномоченный орган ГСГЗ
        'emergency_hq',//Штаб ликвидации ЧС
        'emergency_cost',//Затраты на ликвидацию ЧС, тенге
        'ownership_type',//Форма собственности:
        'escape_routes',//Пути эвакуации:
        'emergency_alarm_systems',//Наличие системы: наблюдения, оповещения, пожарной сигнализации, связи и поддержки действий в случае аварии
        'circs_caused_emergency',//Обстоятельства и причины, приведшие к ЧС (описание)
        'emergency_objects_evolved',//Количество объектов попавших в зону ЧС:
        'guilty_persons_info',//Сведения о виновных лицах
        'investigation_results',//Результаты расследования
        'gov_investigate_commission',//Сведение о создании Правительственной комиссии по расследованию крупного пожара
        'engineering_staff_work',//Проводимая работа инженерно-инспекторским составом
        'latest_tactical_drill',//Сведения о последнем тактическом учении, занятии
        'safety_measures_taken',//Принятые меры по обеспечению техники безопасности
        'possible_emergency_causes',//Анализ возможных причин возникновения ЧС
        'pos_negative_emergency_liqv',//Положительные стороны и недостатки в руководстве ликвидации ЧС
        'measures_prevent_emergency',//О проведенных мероприятиях по предупреждению и снижению тяжести случившегося ЧС
        'rec_measures_prevent_dead',//Рекомендуемые мероприятия по недопущению условий и обстоятельств, способствующие получению травм, гибели
        'conclusion',//Выводы, предложения, меры
    ];

    public function ticket_101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    private function getField($include, $field)
    {
        return $include ? "{$field}||".$this->{$field} : $this->{$field};
    }

    public function parseField($field)
    {
        $nameValue = explode('||', $field);
        if (count($nameValue) == 2) {
            return $nameValue;
        }

        return null;
    }

    public function getInfo($include_names = false)
    {
        $ticket = $this->ticket_101;
        if ($ticket) {
            $result = [
                [
                    'section' => 'info',
                    'section_title' => 'Информационный раздел',
                    'section_index' => 1,
                    'fields' => [
                        'Номер и вид карточки (дата): основная (первая), дополнительная' => $this->getField($include_names,'ticket_type'),
                        'Код ЧС' => $ticket->trip_result->emergency_code ?? null,
                        'Виды ЧС:' => $this->getField($include_names,'emergency_types'),
                        'Дата возникновения ЧС (число, месяц, год)' => $ticket->date_human,
                        'Местонахождение объекта' => $ticket->detailed_address,
                        'Расстояние от места ЧС до ближайшей пожарной части, (км)' => $this->getField($include_names,'distance_emergency_fd'),
                        'Расстояние от места возникновения ЧС до близлежащего населенного пункта' => $this->getField($include_names,'distance_emergency_city'),
                    ],
                ],
                [
                    'section' => 'escalate',
                    'section_title' => 'Развитие и ликвидация ЧС',
                    'section_index' => 2,
                    'fields' => [
                        'Выезд пожарных подразделений:' => $ticket->getDetailedStaffCount(),
                        'Дата и время: обнаружения, сообщения, выезда подразделений' => $ticket->date_human,
                        'Вызов: подтвердился, заведомо ложный' => $ticket->trip_result->name ?? null,
                        'Ликвидировано до прибытия уполномоченного органа ГСГЗ: дата и время, площадь' => $ticket->max_square, // ДАТА И ВРЕМЯ ЧЕГО todo
                        'Скорость и направление ветра' => $this->getField($include_names,'wind_speed_direction'),
                        'Скорость распространения огня м/с' => $this->getField($include_names,'fire_speed'),
                        'Локализации ЧС:' => $ticket->loc_time,
                        'Ликвидации ЧС:' => $ticket->liqv_time,
                        'Условия, способствовавшие развитию ЧС:' => $this->getField($include_names,'circs_emergency_escalate'),
                    ],
                ],
                [
                    'section' => 'aftermath',
                    'section_title' => 'Последствия ЧС',
                    'section_index' => 3,
                    'fields' => [
                        'Сведения о погибших лицах:' => $ticket->people_death_count + $ticket->children_death_count,
                        'Сведения о травмированных лицах:' => $ticket->gpt_burns_count,
                        'Количество спасенных:' => $ticket->rescued_count + $ticket->saved_children,
                        'Количество эвакуированных:' => $ticket->liqv_time,
                        'Нанесенный материальный ущерб (тыс. тенге), всего:' => $this->getField($include_names,'material_damage'),
                        'Погибло животных (птиц), всего:' => $this->getField($include_names,'animals_died'),
                        'Количество спасенных животных (птиц)' => $this->getField($include_names,'animals_saved'),
                        'Уничтожено, всего:' => $this->getField($include_names,'destroyed'),
                        'Повреждено, всего:' => $this->getField($include_names,'damaged'),
                        'Спасено материальных ценностей (тыс. тенге), всего:' => $this->getField($include_names,'wealth_saved'),
                        'Наличие и характер разрушений, обрушений, повреждений (описать)' => $this->getField($include_names,'destruction_description'),
                    ],
                ],
                [
                    'section' => 'forces_resources_liqv',
                    'section_title' => 'Силы и средства на ликвидацию ЧС',
                    'section_index' => 4,
                    'fields' => [
                        'Задействовано в ликвидации, всего:' => $this->getField($include_names,'liquidation_evolved'),
                        'Уполномоченный орган ГСГЗ ' => $this->getField($include_names,'gsgz'),
                        'Дополнительная сила для ликвидации ЧС: уполномоченного органа ГСГЗ, из других заинтересованных органов' => $ticket->special_tech,
                        'Штаб ликвидации ЧС' => $this->getField($include_names,'emergency_hq'),
                        'Руководитель ликвидации ЧС' => $this->head_guards,
                        'Затраты на ликвидацию ЧС, тенге' => $this->getField($include_names,'emergency_cost'),
                    ],
                ],
                [
                    'section' => 'object_characteristics',
                    'section_title' => 'Характеристика объекта',
                    'section_index' => 5,
                    'fields' => [
                        'Наименование объекта (адрес, БИН, телефон, факс и e-mail организации, Ф.И.О. руководителя)' => $ticket->object_name,
                        'Форма собственности:' => $this->getField($include_names,'ownership_type'),
                        'Конструктивные особенности:' => $ticket->building_description,
                        'Пути эвакуации:' => $this->getField($include_names,'escape_routes'),
                        'Наличие системы: наблюдения, оповещения, пожарной сигнализации, связи и поддержки действий в случае аварии' => $this->getField($include_names,'emergency_alarm_systems'),
                    ],
                ],
                [
                    'section' => 'emergency_investigation',
                    'section_title' => 'Расследование ЧС',
                    'section_index' => 6,
                    'fields' => [
                        'Орган, зарегистрировавший информацию об уголовном правонарушении в Книге учета информации Единого реестра досудебных расследований (КУИ ЕРДР)' => $ticket->kui,
                        'Обстоятельства и причины, приведшие к ЧС (описание)' => $this->getField($include_names,'circs_caused_emergency'),
                        'Количество объектов попавших в зону ЧС:' => $this->getField($include_names,'emergency_objects_evolved'),
                        'Сведения о виновных лицах' => $this->getField($include_names,'guilty_persons_info'),
                        'Результаты расследования' => $this->getField($include_names,'investigation_results'),
                        'Сведение о создании Правительственной комиссии по расследованию крупного пожара' => $this->getField($include_names,'gov_investigate_commission'),
                    ],
                ],
                [
                    'section' => 'final_result_info',
                    'section_title' => 'Итоговая справка по результатам расследования, ликвидации ЧС',
                    'section_index' => 7,
                    'fields' => [
                        'Описание происшедшего ЧС' => $ticket->ticket_result,
                        'Проводимая работа инженерно-инспекторским составом' => $this->getField($include_names,'engineering_staff_work'),
                        'Сведения о последнем тактическом учении, занятии' => $this->getField($include_names,'latest_tactical_drill'),
                        'Принятые меры по обеспечению техники безопасности' => $this->getField($include_names,'safety_measures_taken'),
                        'Анализ возможных причин возникновения ЧС' => $this->getField($include_names,'possible_emergency_causes'),
                        'Положительные стороны и недостатки в руководстве ликвидации ЧС' => $this->getField($include_names,'pos_negative_emergency_liqv'),
                        'О проведенных мероприятиях по предупреждению и снижению тяжести случившегося ЧС' => $this->getField($include_names,'measures_prevent_emergency'),
                        'Рекомендуемые мероприятия по недопущению условий и обстоятельств, способствующие получению травм, гибели' => $this->getField($include_names,'rec_measures_prevent_dead'),
                        'Выводы, предложения, меры' => $this->getField($include_names,'conclusion'),
                        'Ответственные лица по заполнению (Ф.И.О, должность, тел., дата исполнения):' => @$ticket->district_manager->name,
                    ],
                ],
            ];
            return collect($result);

        }

        return null;
    }
}
