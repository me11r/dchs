<?php

use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicles = [
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-1',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'ПЧ-1',
            ],
            [
                'name' => 'АЦ-5-70',
                'fire_department_id' => 'ПЧ-1',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-1',
            ],
            [
                'name' => 'АЛ-32',
                'fire_department_id' => 'ПЧ-1',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АПМ',
                'fire_department_id' => 'ПЧ-2',
            ],

            [
                'name' => 'АЛ-30',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АЛ-37',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АБР',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'ПЧ-2',
            ],
            [
                'name' => 'АЦ-2,5-50',
                'fire_department_id' => 'ПЧ-3',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-3',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-3',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-3',
            ],
            [
                'name' => 'Жигули',
                'fire_department_id' => 'ПЧ-3',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЛ-30',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-4',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АЦ-8-40',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АР',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АЛ-32',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'ПЧ-5',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЦ-8-70',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АБР',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЛ-37',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АЦ-8-40',
                'fire_department_id' => 'ПЧ-6',
            ],
            [
                'name' => 'АЦТЛ-32',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АКТП-54',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АСА-20',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'Мотоцикл',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'Мотоцикл',
                'fire_department_id' => 'CПЧ-7',
            ],
            [
                'name' => 'АБР',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЦ-5-70',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЛ-32',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АКТП-90',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'Мотоцикл',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'Мотоцикл',
                'fire_department_id' => 'CПЧ-8',
            ],
            [
                'name' => 'АЦ-1-30',
                'fire_department_id' => 'CПЧ-9',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'CПЧ-9',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'CПЧ-9',
            ],
            [
                'name' => 'АБР',
                'fire_department_id' => 'CПЧ-9',
            ],
            [
                'name' => 'Горный будка',
                'fire_department_id' => 'CПЧ-9',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-10',
            ],
            [
                'name' => 'АЦ-3.2-40',
                'fire_department_id' => 'ПЧ-10',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'ПЧ-10',
            ],
            [
                'name' => 'АЛ-30',
                'fire_department_id' => 'ПЧ-10',
            ],
            [
                'name' => 'Жигули',
                'fire_department_id' => 'ПЧ-10',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АСА-20',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АЛ-30',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'Жигули',
                'fire_department_id' => 'СПЧ-11',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПЧ-12',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'ПЧ-12',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-12',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-12',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПЧ-12',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ПНС-110',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ПНС-110',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АР-2',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АЦ-5-70',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АШ-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АШ-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АШ-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АГДЗС',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АД',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АСАТЭЛ-20',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ОША-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ОША-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Ssang Young',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Ssang Young',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Camry',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Жигули',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Жигули',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Волга',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Волга',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Волга',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Волга',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Грузовой (бортовой)',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ЗИЛ-130 (бортовой)',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АП-5(43202)',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'КС-55713 (Автокран)',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'ПУС',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Вахтовка',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Автобус',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'Автобус',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АСО-20',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'АШ-5',
                'fire_department_id' => 'СО',
            ],
            [
                'name' => 'КШМ',
                'fire_department_id' => 'ЦОУСС',
            ],
            [
                'name' => 'ИПЛ',
                'fire_department_id' => 'ИПЛ',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ШПП',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АЦ-4-40',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'ПНС-110',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АР-2',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АЛ-32',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АЛ-50',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АБР',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АСА-20',
                'fire_department_id' => 'СПЧ-14',
            ],
            [
                'name' => 'АБР-2',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АЦ-4-40',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АЦ-5-40',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АЦ-30-70',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АГДЗС-437043',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АКТП-54',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'АСО-20',
                'fire_department_id' => 'СПЧ-15',
            ],
            [
                'name' => 'Нива',
                'fire_department_id' => 'СПЧ-15',
            ],

            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПП-16',
            ],
            [
                'name' => 'АЦ-7-40',
                'fire_department_id' => 'ПП-16',
            ],
            [
                'name' => 'АЦ-2,5-40',
                'fire_department_id' => 'ПП-17',
            ],
            [
                'name' => 'АЦ-6-40',
                'fire_department_id' => 'ПП-17',
            ],
            [
                'name' => 'АЦ-3,2-40',
                'fire_department_id' => 'ПП-17',
            ],
            [
                'name' => 'АЦ-5-70',
                'fire_department_id' => 'ПП-17',
            ],
        ];

        \App\Models\Vehicle::where('id', '>', 0)->delete();

        foreach ($vehicles as $vehicle) {
            $vehicle['vehicle_type_id'] = \App\Models\VehicleType::name('Основная')->first()->id ?? null;
            $vehicle['fire_department_id'] = \App\FireDepartment::title($vehicle['fire_department_id'])->first()->id ?? null;


            \App\Models\Vehicle::create($vehicle);
        }
    }
}
