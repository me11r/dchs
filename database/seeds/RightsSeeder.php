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
        $groups = [

            ['title' => 'Доступ в систему'],    //1
            ['title' => 'Доступ к заявкам'],    //2
            ['title' => 'Администрирование'],   //3
            ['title' => 'Общие права'],         //4
            ['title' => 'Строевые записки'],    //5
            ['title' => 'Отчеты'],              //6
            ['title' => 'Ввод данных'],         //7
            ['title' => 'Справочники'],         //8
            ['title' => 'Информация'],          //9
            ['title' => 'Messenger'],          //10
        ];

        foreach ($groups as $group) {
            \App\Rights\Group::firstOrCreate($group);
        }

        $rights = [
            ['right_group_id' => 1, 'title' => 'Разрешить входить в систему', 'name' => 'CAN_LOGIN'],

            ['right_group_id' => 2, 'title' => 'Просмотр заявок', 'name' => 'CAN_SEE_REQUEST'],
            ['right_group_id' => 2, 'title' => 'Назначение заявок', 'name' => 'CAN_ASSIGN_REQUEST'],
            ['right_group_id' => 2, 'title' => 'Создание заявок', 'name' => 'CAN_CREATE_REQUEST'],
            ['right_group_id' => 2, 'title' => 'Редактирование заявок', 'name' => 'CAN_EDIT_REQUEST'],
            ['right_group_id' => 2, 'title' => 'Удаление заявок', 'name' => 'CAN_DELETE_REQUEST'],
            ['right_group_id' => 2, 'title' => 'Удаление заявок 101', 'name' => 'DELETE_CARD101'],

            ['right_group_id' => 2, 'title' => 'Изменениие статуса карточки 101 (тип ЧС)', 'name' => 'CAN_CHANGE_CARD101_EMERGENCY_STATUS'],

            ['right_group_id' => 3, 'title' => 'Управление пользователями системы', 'name' => 'CAN_MANAGE_USERS'],
            ['right_group_id' => 3, 'title' => 'Редактирование справочников', 'name' => 'CAN_EDIT_DICTIONARIES'],

            ['right_group_id' => 4, 'title' => 'Получение путевых листов ПЧ', 'name' => 'CAN_SEE_TRIP_PLAN'],
            ['right_group_id' => 4, 'title' => 'Может смотреть суточный отчет', 'name' => 'CAN_SEE_DAILY_REPORT'],

            ['title' => 'СП и АСР', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_101'],
            ['title' => 'РОСО', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_ROSO'],
            ['title' => 'ЦМК', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_CMK'],
            ['title' => 'ГУ "Казселезащита"', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION'],
            ['title' => 'АО"Казавиаспас"', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_AIR_RESCUE'],
            ['title' => 'АО "Өртсөндіруші"', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_REPORT_ORTSERT'],
            ['title' => 'ДЧС г.Алматы', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_DCHS_ALMATY'],
            ['title' => 'Служба спасения г.Алматы', 'right_group_id' => 5, 'name' => 'CAN_ACCESS_FORMATION_EMERGENCY_ALMATY'],
            ['title' => 'СП и АСР: редактирование ОД', 'right_group_id' => 5, 'name' => 'CAN_EDIT_OD_FORMATION'],

            ['title' => 'Может одобрять суточный отчет', 'right_group_id' => 5, 'name' => 'CAN_APPROVE_FORMATION_REPORT_101'],
            ['title' => 'Может утверждать Журнал строевых записок ДЧС г.Алматы', 'right_group_id' => 5, 'name' => 'CAN_APPROVE_FORMATION_RECORD'],
            ['title' => 'Может редактировать Журнал строевых записок ДЧС г.Алматы после утверждения', 'right_group_id' => 5, 'name' => 'CAN_EDIT_APPROVED_FORMATION_RECORD'],

            ['title' => 'Получать уведомления о заполнении строевой / оперативной информации', 'right_group_id' => 5, 'name' => 'CAN_RECEIVE_NOTIFICATION_FORMATION_RECORD'],
            ['title' => 'Отправлять уведомления о заполнении строевой / оперативной информации', 'right_group_id' => 5, 'name' => 'CAN_SEND_NOTIFICATION_FORMATION_RECORD'],

            ['right_group_id' => 6, 'title' => 'Может смотреть информацию', 'name' => 'CAN_ACCESS_INFO'],
            ['right_group_id' => 6, 'title' => 'Может смотреть оперативную информацию', 'name' => 'CAN_ACCESS_OPER_INFO'],
            ['right_group_id' => 6, 'title' => 'Может смотреть отчет по ЛС', 'name' => 'CAN_ACCESS_PERORT_PERSONS'],
            ['right_group_id' => 6, 'title' => 'Может смотреть отчет по технике', 'name' => 'CAN_ACCESS_PERORT_TECH'],

            ['right_group_id' => 6, 'title' => 'Данные по СРУ: просмотр', 'name' => 'SIREN_SPEECH_TECH_SHOW'],
            ['right_group_id' => 6, 'title' => 'Данные по СРУ: создание', 'name' => 'SIREN_SPEECH_TECH_CREATE'],
            ['right_group_id' => 6, 'title' => 'Данные по СРУ: редактирование', 'name' => 'SIREN_SPEECH_TECH_EDIT'],
            ['right_group_id' => 6, 'title' => 'Данные по СРУ: удаление', 'name' => 'SIREN_SPEECH_TECH_DELETE'],

            ['right_group_id' => 6, 'title' => 'Информация по звонкам: просмотр', 'name' => 'CALL_INFO_SHOW'],
            ['right_group_id' => 6, 'title' => 'Информация по звонкам: создание', 'name' => 'CALL_INFO_CREATE'],
            ['right_group_id' => 6, 'title' => 'Информация по звонкам: редактирование', 'name' => 'CALL_INFO_EDIT'],
            ['right_group_id' => 6, 'title' => 'Информация по звонкам: удаление', 'name' => 'CALL_INFO_DELETE'],

            ['right_group_id' => 6, 'title' => 'Аналитика101: просмотр', 'name' => 'ANALYTICS101_SHOW'],
            ['right_group_id' => 6, 'title' => 'Аналитика101: редактирование', 'name' => 'ANALYTICS101_EDIT'],
            ['right_group_id' => 6, 'title' => 'Аналитика101: удаление', 'name' => 'ANALYTICS101_DELETE'],

            ['right_group_id' => 7, 'title' => 'Ручной ввод хронометража', 'name' => 'CAN_ACCESS_MANUAL_INPUT_CHRONO'],
            ['right_group_id' => 7, 'title' => 'Расположение гидрантов', 'name' => 'CAN_ACCESS_HYDRANT'],
            ['right_group_id' => 7, 'title' => 'Транспортные средства', 'name' => 'CAN_ACCESS_TECH'],
            ['right_group_id' => 7, 'title' => 'Личный состав', 'name' => 'CAN_ACCESS_PERSONS'],
            ['right_group_id' => 7, 'title' => 'Пожарные части', 'name' => 'CAN_ACCESS_FIRE_DEPTS'],
            ['right_group_id' => 7, 'title' => 'Моренные озера', 'name' => 'CAN_ACCESS_FIRE_LAKES'],
            ['right_group_id' => 7, 'title' => 'Редактировать данные по гидрантам в рамках своей ПЧ', 'name' => 'CAN_EDIT_MAP_HYDRANTS'],
            ['right_group_id' => 7, 'title' => 'Может изменять список гидрантов', 'name' => 'CAN_EDIT_HYDRANT_LOCATIONS'],
            ['right_group_id' => 7, 'title' => 'Личный состав: полный просмотр', 'name' => 'STAFF_FULL_VIEW_ACCESS'],
            ['right_group_id' => 7, 'title' => 'Транспортные средства: полный просмотр', 'name' => 'VEHICLES_FULL_VIEW_ACCESS'],

            ['title' => 'Строевые: только чтение', 'right_group_id' => 5, 'name' => 'CAN_READ_ONLY_FORMATION'],

            ['right_group_id' => 4, 'title' => 'Получение путевых листов служб взаимодействия', 'name' => 'CAN_RECEIVE_SERVICE_PLAN'],

            ['right_group_id' => 2, 'title' => 'Просмотр заявок 112', 'name' => 'CAN_VIEW_112_CARD'],
            ['right_group_id' => 2, 'title' => 'Назначение заявок 112', 'name' => 'CAN_ASSIGN_112_CARD'],
            ['right_group_id' => 2, 'title' => 'Создание заявок 112', 'name' => 'CAN_CREATE_112_CARD'],
            ['right_group_id' => 2, 'title' => 'Редактирование заявок 112', 'name' => 'CAN_EDIT_112_CARD'],
            ['right_group_id' => 2, 'title' => 'Удаление заявок 112', 'name' => 'CAN_DELETE_112_CARD'],

            ['right_group_id' => 6, 'title' => 'Просмотр оперативной информации (все службы)', 'name' => 'CAN_SEE_ALL_EMERGENCY_SITUATIONS'],
        ];

        $rights[] = ['right_group_id' => 8, 'title' => 'Пожарные части (справочник)', 'name' => 'DICT_FIRE_DEPARTMENTS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Типы инцидентов', 'name' => 'DICT_INCIDENT_TYPES'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Опер планы', 'name' => 'DICT_OPERATIONAL_PLANS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Опер карточки', 'name' => 'DICT_OPERATIONAL_CARDS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Типы воздушных судов', 'name' => 'DICT_AIRCRAFT_TYPES'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Воздушные суда', 'name' => 'DICT_AIRCRAFTS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'Ответственные по районам', 'name' => 'DICT_DISTRICT_MANAGERS'];

        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ЦППС', 'name' => 'DICT_CPPS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ЕДДС', 'name' => 'DICT_EDDS'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ИПЛ', 'name' => 'DICT_IPL'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - Страший мастер связи', 'name' => 'DICT_SENIOR_COMMUNICATION_MASTER'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - Водоканал', 'name' => 'DICT_WATER_CANAL'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ЦРБ', 'name' => 'DICT_CRB'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - База ГДЗС', 'name' => 'DICT_GDZS_BASE'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - Врач', 'name' => 'DICT_DOCTOR'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - Оперативные дежурные автомашины', 'name' => 'DICT_STAFF_DUTY_VEHICLE'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - КШМ', 'name' => 'DICT_KSHM'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ИПЛ "Жалын"', 'name' => 'DICT_ZHALIN'];
        $rights[] = ['right_group_id' => 8, 'title' => 'ЛС - ДСПТ', 'name' => 'DICT_DSPT'];

        $rights[] = ['right_group_id' => 6, 'title' => 'РГП Казгидромет (заполнение данных)', 'name' => 'KAZGIDROMET_FILLING'];

        $rights[] = ['right_group_id' => 2, 'title' => 'Карточка 101: редактирование после закрытия', 'name' => 'CARD101_EDIT_CLOSED'];

        $rights[] = ['right_group_id' => 2, 'title' => 'Карточка 101: Прочие 101', 'name' => 'CARD101_ACCESS_OTHERS_RIDES'];
        $rights[] = ['right_group_id' => 2, 'title' => 'Карточка 101: Прочие 101: удаление', 'name' => 'CARD101_OTHERS_RIDES_CAN_DELETE'];

        $rights[] = ['right_group_id' => 2, 'title' => 'Карточка 101: Учебные 101', 'name' => 'CARD101_ACCESS_DRILL_RIDES'];
        $rights[] = ['right_group_id' => 2, 'title' => 'Карточка 101: Учебные 101: удаление', 'name' => 'CARD101_DRILL_RIDES_CAN_DELETE'];


        $rights[] = ['right_group_id' => 9, 'title' => 'Сводная информация по мореным озерам: просмотр', 'name' => 'CAN_VIEW_MORAINIC_LAKE_SUMMARIES'];
        $rights[] = ['right_group_id' => 9, 'title' => 'Сводная информация по мореным озерам: создание', 'name' => 'CAN_CREATE_MORAINIC_LAKE_SUMMARIES'];
        $rights[] = ['right_group_id' => 9, 'title' => 'Сводная информация по мореным озерам: редактирование', 'name' => 'CAN_EDIT_MORAINIC_LAKE_SUMMARIES'];

        $rights[] = ['right_group_id' => 9, 'title' => 'СОМЭ: просмотр', 'name' => 'CAN_VIEW_QUAKES'];
        $rights[] = ['right_group_id' => 9, 'title' => 'СОМЭ: создание', 'name' => 'CAN_CREATE_QUAKES'];
        $rights[] = ['right_group_id' => 9, 'title' => 'СОМЭ: редактирование', 'name' => 'CAN_EDIT_QUAKES'];
        $rights[] = ['right_group_id' => 9, 'title' => 'СОМЭ: удаление', 'name' => 'CAN_DELETE_QUAKES'];

        $rights[] = ['right_group_id' => 9, 'title' => 'ГУ "Казселезащита": просмотр', 'name' => 'CAN_VIEW_MUDFLOW_PROTECTION'];
        $rights[] = ['right_group_id' => 9, 'title' => 'ГУ "Казселезащита": редактирование', 'name' => 'CAN_EDIT_MUDFLOW_PROTECTION'];

        $rights[] = ['right_group_id' => 10, 'title' => 'Может инициировать массовую рассылку', 'name' => 'CAN_DO_MASS_SENDING'];




        foreach (\App\Dictionary::all() as $dict) {
            $rights[] = ['right_group_id' => 8, 'title' => $dict->title, 'name' => mb_strtoupper(str_start($dict->table, 'dict_'))];
        }

        foreach ($rights as $item) {
            \App\Right::updateOrCreate(['title' => $item['title']], $item);
        }


    }
}
