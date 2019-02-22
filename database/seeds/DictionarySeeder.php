<?php

class DictionarySeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $dicts = [
            [
                'title' => 'Объект возгорания',
                'table' => 'dict_fire_object',
                'model' => \App\Dictionary\FireObject::class
            ],
            [
                'title' => 'Район города',
                'table' => 'dict_city_area',
                'model' => \App\Dictionary\CityArea::class
            ],
            [
                'title' => 'Класс пожара',
                'table' => 'dict_fire_level',
                'model' => \App\Dictionary\FireLevel::class
            ],
            [
                'title' => 'Способ ликвидации',
                'table' => 'dict_liquidation_method',
                'model' => \App\Dictionary\LiquidationMethod::class
            ],
            [
                'title' => 'Объект горения',
                'table' => 'dict_burn_object',
                'model' => \App\Dictionary\BurntObject::class
            ],
            [
                'title' => 'Причина выезда',
                'table' => 'dict_trip_result',
                'model' => \App\Dictionary\TripResult::class
            ],
            [
                'title' => 'Типы служб',
                'table' => 'service_types',
                'model' => \App\Models\ServiceType::class
            ],
            [
                'title' => 'Стволы',
                'table' => 'dict_trunk',
                'model' => \App\Models\Trunk::class
            ],
            [
                'title' => 'Источник противопожарного водоснабжения',
                'table' => 'water_supply_sources',
                'model' => \App\Dictionary\WaterSupplySource::class
            ],
            [
                'title' => 'ОДС',
                'table' => 'oper_duty_shifts',
                'model' => \App\OperDutyShift::class
            ],
            [
                'title' => 'ЛС ОДС',
                'table' => 'oper_duty_shift_staffs',
                'model' => \App\OperDutyShiftStaff::class
            ],

            [
                'title' => 'ЛС - ЦППС',
                'table' => 'staff_cpps',
                'model' => \App\StaffCpps::class
            ],
            [
                'title' => 'ЛС - ЕДДС',
                'table' => 'staff_edds',
                'model' => \App\StaffEdds::class
            ],
            [
                'title' => 'ЛС - ИПЛ',
                'table' => 'staff_ipls',
                'model' => \App\StaffIpl::class
            ],
            [
                'title' => 'ЛС - Страший мастер связи',
                'table' => 'staff_senior_communication_masters',
                'model' => \App\StaffSeniorCommunicationMaster::class
            ],
            [
                'title' => 'ЛС - Водоканал',
                'table' => 'staff_water_canals',
                'model' => \App\StaffWaterCanal::class
            ],
            [
                'title' => 'ЛС - ЦРБ',
                'table' => 'staff_crbs',
                'model' => \App\StaffCrb::class
            ],
            [
                'title' => 'ЛС - База ГДЗС',
                'table' => 'staff_gdzs_bases',
                'model' => \App\StaffGdzsBase::class
            ],
            [
                'title' => 'ЛС - Врач',
                'table' => 'staff_doctors',
                'model' => \App\StaffDoctor::class
            ],
            [
                'title' => 'ЛС - Оперативные дежурные автомашины',
                'table' => 'staff_duty_vehicles',
                'model' => \App\StaffDutyVehicle::class
            ],
            [
                'title' => 'ЛС - КШМ',
                'table' => 'staff_kshms',
                'model' => \App\StaffKshm::class
            ],
            [
                'title' => 'ЛС - ИПЛ "Жалын"',
                'table' => 'staff_zhalins',
                'model' => \App\StaffZhalin::class
            ],
            [
                'title' => 'ЛС - ДСПТ',
                'table' => 'staff_dspts',
                'model' => \App\StaffDspt::class
            ],
            [
                'title' => 'Нормативно-справочная информация',
                'table' => 'event_infos',
                'model' => \App\EventInfo::class
            ],
//            [
//                'title' => 'Нормативно-справочная информация: на месте',
//                'table' => 'event_info_arriveds',
//                'model' => \App\EventInfoArrived::class
//            ],
            [
                'title' => 'Типы выездов',
                'table' => 'ride_types',
                'model' => \App\RideType::class
            ],
            [
                'title' => 'Моренные озера',
                'table' => 'morainic_lakes',
                'model' => \App\Models\MorainicLake::class
            ],
            [
                'title' => 'Персоны суточного отчета',
                'table' => 'daily_report_persons',
                'model' => \App\Models\DailyReportPerson::class
            ],
            [
                'title' => "Тип жилого сектора",
                'table' => 'living_sector_types',
                'model' => \App\LivingSectorType::class
            ],
            [
                'title' => "Номер караула",
                'table' => 'guard_numbers',
                'model' => \App\GuardNumber::class
            ],
            [
                'title' => "Оперативные группы (ОГ)",
                'table' => 'operational_groups',
                'model' => \App\OperationalGroup::class
            ],
            [
                'title' => "Статус а/м",
                'table' => 'vehicle_statuses',
                'model' => \App\VehicleStatus::class
            ],
            [
                'title' => "Тип происшествия",
                'table' => 'emergency_types',
                'model' => \App\EmergencyType::class
            ],
            [
                'title' => "Номер норматива",
                'table' => 'norm_numbers',
                'model' => \App\NormNumber::class
            ],
            [
                'title' => "Тип норматива",
                'table' => 'norm_types',
                'model' => \App\NormType::class
            ],
            [
                'title' => "Классификация объектов",
                'table' => 'object_classifications',
                'model' => \App\ObjectClassification::class
            ],
            [
                'title' => "Типы учений",
                'table' => 'drill_types',
                'model' => \App\DrillType::class
            ],
            [
                'title' => "Название ЧС",
                'table' => 'emergency_names',
                'model' => \App\EmergencyName::class
            ],
            [
                'title' => "Типы стволов",
                'table' => 'trunk_types',
                'model' => \App\TrunkType::class
            ],
            [
                'title' => "Место подтопления",
                'table' => 'flooding_places',
                'model' => \App\FloodingPlace::class
            ],
            [
                'title' => "Причина подтопления",
                'table' => 'flooding_reasons',
                'model' => \App\FloodingReason::class
            ],
        ];
        #Schema::disableForeignKeyConstraints();
        #(new App\Dictionary)->truncate();
        foreach ($dicts as $dict) {
            (new App\Dictionary())->firstOrCreate($dict);
        }
        /*$this->call(WaterSupplySeeder::class);
        $this->call(FireObjectSeeder::class);
        $this->call(CityAreaSeeder::class);
        $this->call(FireDeptSeeder::class);
        $this->call(FireLevelSeeder::class);
        $this->call(LiqvidationMethodSeeder::class);
        $this->call(BurntObjectSeeder::class);
        $this->call(TripResultSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(IncidentTypeSeeder::class);
        $this->call(OperationalPlanSeeder::class);
        $this->call(RiverSeeder::class);
        $this->call(TrunkSeeder::class);*/
        //Schema::enableForeignKeyConstraints();
    }
}
