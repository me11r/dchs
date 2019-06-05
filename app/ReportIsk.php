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
                    'section_title' => t('/reports/isk.sections.section_info.title'),//'Информационный раздел',
                    'section_index' => 1,
                    'fields' => [
                        t('/reports/isk.sections.section_info.number') => $this->getField($include_names,'ticket_type'),/*'Номер и вид карточки (дата): основная (первая), дополнительная' */
                        t('/reports/isk.sections.section_info.code') => $ticket->trip_result->emergency_code ?? null,/*'Код ЧС' */
                        t('/reports/isk.sections.section_info.emergency_types') => $this->getField($include_names,'emergency_types'), /*'Виды ЧС:' */
                        t('/reports/isk.sections.section_info.date') => $ticket->date_human, /*'Дата возникновения ЧС (число, месяц, год)' */
                        t('/reports/isk.sections.section_info.location') => $ticket->detailed_address,/*'Местонахождение объекта' */
                        t('/reports/isk.sections.section_info.distance_emergency_fd') => $this->getField($include_names,'distance_emergency_fd'),/*'Расстояние от места ЧС до ближайшей пожарной части, (км)' */
                        t('/reports/isk.sections.section_info.distance_emergency_town') => $this->getField($include_names,'distance_emergency_city'), /*'Расстояние от места возникновения ЧС до близлежащего населенного пункта' */
                    ],
                ],
                [
                    'section' => 'escalate',
                    'section_title' => t('/reports/isk.sections.section_escalate.title'),//'Развитие и ликвидация ЧС',
                    'section_index' => 2,
                    'fields' => [
                        t('/reports/isk.sections.section_escalate.fd_out') => $ticket->getDetailedStaffCount()/*'Выезд пожарных подразделений:' */,
                        t('/reports/isk.sections.section_escalate.date') => $ticket->date_human,/*'Дата и время: обнаружения, сообщения, выезда подразделений' */
                        t('/reports/isk.sections.section_escalate.call_type') => $ticket->trip_result->name ?? null, /*'Вызов: подтвердился, заведомо ложный' */
                        t('/reports/isk.sections.section_escalate.date_square') => $ticket->max_square, // ДАТА И ВРЕМЯ 'Ликвидировано до прибытия уполномоченного органа ГСГЗ: дата и время, площадь'
                        t('/reports/isk.sections.section_escalate.wind_velocity') => $this->getField($include_names,'wind_speed_direction'), /*'Скорость и направление ветра' */
                        t('/reports/isk.sections.section_escalate.fire_velocity') => $this->getField($include_names,'fire_speed'),/*'Скорость распространения огня м/с' */
                        t('/reports/isk.sections.section_escalate.loc') => $ticket->loc_time,/*'Локализации ЧС:' */
                        t('/reports/isk.sections.section_escalate.liqv') => $ticket->liqv_time, /*'Ликвидации ЧС:' */
                        t('/reports/isk.sections.section_escalate.escalate_reasons') => $this->getField($include_names,'circs_emergency_escalate'),/*'Условия, способствовавшие развитию ЧС:' */
                    ],
                ],
                [
                    'section' => 'aftermath',
                    'section_title' => t('/reports/isk.sections.section_aftermath.title'),//'Последствия ЧС',
                    'section_index' => 3,
                    'fields' => [
                        t('/reports/isk.sections.section_aftermath.dead') => $ticket->people_death_count + $ticket->children_death_count,/*'Сведения о погибших лицах:' */
                        t('/reports/isk.sections.section_aftermath.injured') => $ticket->gpt_burns_count, /*'Сведения о травмированных лицах:'*/
                        t('/reports/isk.sections.section_aftermath.saved') => $ticket->rescued_count + $ticket->saved_children,/*'Количество спасенных:'*/
                        t('/reports/isk.sections.section_aftermath.evacuated') => $ticket->liqv_time,/*'Количество эвакуированных:'*/
                        t('/reports/isk.sections.section_aftermath.material_damage') => $this->getField($include_names,'material_damage'),/*'Нанесенный материальный ущерб (тыс. тенге), всего:'*/
                        t('/reports/isk.sections.section_aftermath.dead_animals') => $this->getField($include_names,'animals_died'),/*'Погибло животных (птиц), всего:'*/
                        t('/reports/isk.sections.section_aftermath.saved_animals') => $this->getField($include_names,'animals_saved'),/*'Количество спасенных животных (птиц)'*/
                        t('/reports/isk.sections.section_aftermath.destroyed') => $this->getField($include_names,'destroyed'),/*'Уничтожено, всего:'*/
                        t('/reports/isk.sections.section_aftermath.damaged') => $this->getField($include_names,'damaged'),/*'Повреждено, всего:'*/
                        t('/reports/isk.sections.section_aftermath.saved_material') => $this->getField($include_names,'wealth_saved'),/*'Спасено материальных ценностей (тыс. тенге), всего:'*/
                        t('/reports/isk.sections.section_aftermath.character_note') => $this->getField($include_names,'destruction_description'),/*'Наличие и характер разрушений, обрушений, повреждений (описать)'*/
                    ],
                ],
                [
                    'section' => 'forces_resources_liqv',
                    'section_title' => t('/reports/isk.sections.section_forces_resources_liqv.title'),//'Силы и средства на ликвидацию ЧС',
                    'section_index' => 4,
                    'fields' => [
                        t('/reports/isk.sections.section_forces_resources_liqv.liqv_total') => $this->getField($include_names,'liquidation_evolved'),/*'Задействовано в ликвидации, всего:'*/
                        t('/reports/isk.sections.section_forces_resources_liqv.gsgz') => $this->getField($include_names,'gsgz'),/*'Уполномоченный орган ГСГЗ '*/
                        t('/reports/isk.sections.section_forces_resources_liqv.additional_force') => $ticket->special_tech,/*'Дополнительная сила для ликвидации ЧС: уполномоченного органа ГСГЗ, из других заинтересованных органов'*/
                        t('/reports/isk.sections.section_forces_resources_liqv.hq') => $this->getField($include_names,'emergency_hq'),/*'Штаб ликвидации ЧС'*/
                        t('/reports/isk.sections.section_forces_resources_liqv.emergency_leader') => $this->head_guards,/*'Руководитель ликвидации ЧС'*/
                        t('/reports/isk.sections.section_forces_resources_liqv.emergency_costs') => $this->getField($include_names,'emergency_cost'),/*'Затраты на ликвидацию ЧС, тенге'*/
                    ],
                ],
                [
                    'section' => 'object_characteristics',
                    'section_title' => t('/reports/isk.sections.section_object_characteristics.title'),//'Характеристика объекта',
                    'section_index' => 5,
                    'fields' => [
                        t('/reports/isk.sections.section_object_characteristics.name') => $ticket->object_name,/*'Наименование объекта (адрес, БИН, телефон, факс и e-mail организации, Ф.И.О. руководителя)'*/
                        t('/reports/isk.sections.section_object_characteristics.form_type') => $this->getField($include_names,'ownership_type'), /*'Форма собственности:' */
                        t('/reports/isk.sections.section_object_characteristics.construction_features') => $ticket->building_description,/*'Конструктивные особенности:'*/
                        t('/reports/isk.sections.section_object_characteristics.evacuation_routes') => $this->getField($include_names,'escape_routes'),/*'Пути эвакуации:'*/
                        t('/reports/isk.sections.section_object_characteristics.systems_exist') => $this->getField($include_names,'emergency_alarm_systems'),/*'Наличие системы: наблюдения, оповещения, пожарной сигнализации, связи и поддержки действий в случае аварии'*/
                    ],
                ],
                [
                    'section' => 'emergency_investigation',
                    'section_title' => t('/reports/isk.sections.section_emergency_investigation.title'),//'Расследование ЧС',
                    'section_index' => 6,
                    'fields' => [
                        t('/reports/isk.sections.section_emergency_investigation.registration_department') => $ticket->kui,/*'Орган, зарегистрировавший информацию об уголовном правонарушении в Книге учета информации Единого реестра досудебных расследований (КУИ ЕРДР)'*/
                        t('/reports/isk.sections.section_emergency_investigation.emergency_reasons') => $this->getField($include_names,'circs_caused_emergency'),/*'Обстоятельства и причины, приведшие к ЧС (описание)'*/
                        t('/reports/isk.sections.section_emergency_investigation.emergency_objects') => $this->getField($include_names,'emergency_objects_evolved'),/*'Количество объектов попавших в зону ЧС:'*/
                        t('/reports/isk.sections.section_emergency_investigation.guilty_persons_info') => $this->getField($include_names,'guilty_persons_info'), /*'Сведения о виновных лицах' */
                        t('/reports/isk.sections.section_emergency_investigation.investigation_results') => $this->getField($include_names,'investigation_results'), /*'Результаты расследования' */
                        t('/reports/isk.sections.section_emergency_investigation.gov_investigate_commission') => $this->getField($include_names,'gov_investigate_commission'),/*'Сведение о создании Правительственной комиссии по расследованию крупного пожара' */
                    ],
                ],
                [
                    'section' => 'final_result_info',
                    'section_title' => t('/reports/isk.sections.section_final_result_info.title'),/*'Итоговая справка по результатам расследования, ликвидации ЧС'*/
                    'section_index' => 7,
                    'fields' => [
                        t('/reports/isk.sections.section_final_result_info.emergency_description') => $ticket->ticket_result,/*'Описание происшедшего ЧС'*/
                        t('/reports/isk.sections.section_final_result_info.engineering_staff_work') => $this->getField($include_names,'engineering_staff_work'),/*'Проводимая работа инженерно-инспекторским составом'*/
                        t('/reports/isk.sections.section_final_result_info.latest_tactical_drill') => $this->getField($include_names,'latest_tactical_drill'),/*'Сведения о последнем тактическом учении, занятии'*/
                        t('/reports/isk.sections.section_final_result_info.safety_measures_taken') => $this->getField($include_names,'safety_measures_taken'),/*'Принятые меры по обеспечению техники безопасности'*/
                        t('/reports/isk.sections.section_final_result_info.possible_emergency_causes') => $this->getField($include_names,'possible_emergency_causes'),/*'Анализ возможных причин возникновения ЧС'*/
                        t('/reports/isk.sections.section_final_result_info.pos_negative_emergency_liqv') => $this->getField($include_names,'pos_negative_emergency_liqv'),/*'Положительные стороны и недостатки в руководстве ликвидации ЧС'*/
                        t('/reports/isk.sections.section_final_result_info.measures_prevent_emergency') => $this->getField($include_names,'measures_prevent_emergency'),/*'О проведенных мероприятиях по предупреждению и снижению тяжести случившегося ЧС'*/
                        t('/reports/isk.sections.section_final_result_info.rec_measures_prevent_dead') => $this->getField($include_names,'rec_measures_prevent_dead'),/*'Рекомендуемые мероприятия по недопущению условий и обстоятельств, способствующие получению травм, гибели'*/
                        t('/reports/isk.sections.section_final_result_info.conclusion') => $this->getField($include_names,'conclusion'),/*'Выводы, предложения, меры'*/
                        t('/reports/isk.sections.section_final_result_info.names') => @$ticket->district_manager->name,/*'Ответственные лица по заполнению (Ф.И.О, должность, тел., дата исполнения):'*/
                    ],
                ],
            ];
            return collect($result);

        }

        return null;
    }
}
