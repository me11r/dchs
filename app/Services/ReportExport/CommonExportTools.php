<?php

namespace App\Services\ReportExport;


use App\FireDepartment;
use App\FormationReport;
use App\FormationTechReport;
use App\OperationalGroupSchedule;
use Carbon\Carbon;

trait CommonExportTools
{
    /**
     * @return array
     */
    private function getFirstTableSumRow()
    {
        return [
            'Итого', // Наименование пожарных подразделений

            $this->sumPeople['total'], // В карауле по списку л/с

            $this->sumPeople['active'] , // На лицо личного состава -> Всего  . ($this->data['totalTraineeCount']['active'] ? "/".$this->data['totalTraineeCount']['active'] : '')
            $this->sumPeople['head_guards'].($this->data['totalTraineeCount']['head_guards'] ? "/".$this->data['totalTraineeCount']['head_guards'] : ''), // На лицо личного состава -> Нач. караулов
            $this->sumPeople['commander_squads'].($this->data['totalTraineeCount']['commander_squads'] ? "/".$this->data['totalTraineeCount']['commander_squads'] : ''), // На лицо личного состава -> Ком. отделений
            $this->sumPeople['drivers'].($this->data['totalTraineeCount']['drivers'] ? "/".$this->data['totalTraineeCount']['drivers'] : ''), // На лицо личного состава -> Шоферы
            $this->sumPeople['privates'].($this->data['totalTraineeCount']['privates'] ? "/".$this->data['totalTraineeCount']['privates'] : ''), // На лицо личного состава -> Ряд. состав
            $this->sumPeople['dispatchers'].($this->data['totalTraineeCount']['dispatchers'] ? "/".$this->data['totalTraineeCount']['dispatchers'] : ''), // На лицо личного состава -> Диспетчеров

            $this->sumPeople['vacation'], // Отсутствуют -> Отпуск
            $this->sumPeople['study'], // Отсутствуют -> Учебный
            $this->sumPeople['maternity'], // Отсутствуют -> Декрет
            $this->sumPeople['sick'], // Отсутствуют -> Больные
            $this->sumPeople['business_trip'], // Отсутствуют -> Командировка
            $this->sumPeople['other'], // Отсутствуют -> Др. причины

            $this->sumPeople['gas_smoke_protection_service'], // ГДЗС
            $this->data['sumArray']['tech']['asv_dask'] ?? null, // АСВ + ДАСК

            $this->data['sumArray']['tech']['motor_water_pump'] ."/". $this->data['sumArray']['tech']['motor_mud_pump'], //'-', // Мотопомпы Водяная/Грязевая

            $this->data['tech_items_count']['tech_action_type_1'], // Пожарная техника ->  В боевом расчёте -> Тип основ пожарного а/м
            $this->data['tech_items_count']['tech_action_type_2'], // Пожарная техника ->  В боевом расчёте -> Марка спец. пожарного а/м Мотоциклы

            $this->data['tech_items_count']['tech_reserve_type_1'], // Пожарная техника ->  В резерве -> Тип основ пожарного а/м
            $this->data['tech_items_count']['tech_reserve_type_2'], // Пожарная техника ->  В резерве -> Марка спец. пожарных а/м

            $this->data['tech_items_count']['tech_repair_type_1'], // Пожарная техника ->  На ремонте -> Тип основ пожарного а/м
            $this->data['tech_items_count']['tech_repair_type_2'], // Пожарная техника ->  На ремонте -> Марка спец. пожарных а/м
        ];
    }

    private function getTableSumRow($rows)
    {
        $result = [];
        foreach ($rows as $row_index => $cells) {
            foreach ($cells as $index => $cell) {

                if(!isset($result[$index])) {
                    $result[$index] = 0;
                }

                if(is_numeric($cell)) {
                    $result[$index] += $cell;
                }
                else {
                    $result[$index] += 0;
                }
            }
        }

        return $result;
    }

    /**
     * @param FireDepartment $department
     * @return array
     */
    private function getFirstTableRowForDepartment(FireDepartment $department)
    {
        $id = $department->id;

        $peopleData = array_get($this->people, $id, []);
        $techData = array_get($this->tech, $id, []);
//        $delimiter = '<w:br/>';
        $delimiter = ', ';

        return [
            $department->name, // Наименование пожарных подразделений

            array_get($peopleData, 'total', 0), // В карауле по списку л/с

            array_get($peopleData, 'active', 0), // На лицо личного состава -> Всего  .($peopleData && $peopleData->getTraineeCount('active') ? '/'.$peopleData->getTraineeCount('active') : '')
            array_get($peopleData, 'head_guards', 0) .($peopleData && $peopleData->getTraineeCount('head_guards') ? '/'.$peopleData->getTraineeCount('head_guards') : ''), // На лицо личного состава -> Нач. караулов
            array_get($peopleData, 'commander_squads', 0).($peopleData && $peopleData->getTraineeCount('commander_squads') ? '/'.$peopleData->getTraineeCount('commander_squads') : ''), // На лицо личного состава -> Ком. отделений
            array_get($peopleData, 'drivers', 0).($peopleData && $peopleData->getTraineeCount('drivers') ? '/'.$peopleData->getTraineeCount('drivers') : ''), // На лицо личного состава -> Шоферы
            array_get($peopleData, 'privates', 0) .($peopleData && $peopleData->getTraineeCount('privates') ? '/'.$peopleData->getTraineeCount('privates') : ''), // На лицо личного состава -> Ряд. состав
            array_get($peopleData, 'dispatchers', 0).($peopleData && $peopleData->getTraineeCount('dispatchers') ? '/'.$peopleData->getTraineeCount('dispatchers') : ''), // На лицо личного состава -> Диспетчеров

            array_get($peopleData, 'vacation', 0), // Отсутствуют -> Отпуск
            array_get($peopleData, 'study', 0), // Отсутствуют -> Учебный
            array_get($peopleData, 'maternity', 0), // Отсутствуют -> Декрет
            array_get($peopleData, 'sick', 0), // Отсутствуют -> Больные
            array_get($peopleData, 'business_trip', 0), // Отсутствуют -> Командировка
            array_get($peopleData, 'other', 0), // Отсутствуют -> Др. причины

            array_get($peopleData, 'gas_smoke_protection_service', 0), // ГДЗС
            array_get($techData, 'asv_dask', 0), // АСВ + ДАСК

            isset($this->tech[$id]) ? (int)array_get($techData, 'motor_water_pump', 0) . '/' . (int)array_get($techData, 'motor_mud_pump', 0) : '0/0', // Мотопомпы Водяная/Грязевая

            $department->tech_action ? implode($delimiter, $department->tech_action->where('vehicle.vehicle_type_id', '=', 1)->pluck('vehicle_name_status')->toArray()) : '', // Пожарная техника ->  В боевом расчёте -> Тип основ пожарного а/м
            $department->tech_action ? implode($delimiter, $department->tech_action->whereIn('vehicle.vehicle_type_id', [2,3])->pluck('vehicle_name_status')->toArray()) : '', // Пожарная техника ->  В боевом расчёте -> Марка спец. пожарного а/м Мотоциклы

            $department->tech_reserve ? implode($delimiter, $department->tech_reserve->where('vehicle.vehicle_type_id', '=', 1)->pluck('vehicle_name_status')->toArray()) : '', // Пожарная техника ->  В резерве -> Тип основ пожарного а/м
            $department->tech_reserve ? implode($delimiter, $department->tech_reserve->whereIn('vehicle.vehicle_type_id', [2,3])->pluck('vehicle_name_status')->toArray()) : '', // Пожарная техника ->  В резерве -> Марка спец. пожарных а/м

            $department->tech_repair ? implode($delimiter, $department->tech_repair->where('vehicle.vehicle_type_id', '=', 1)->pluck('vehicle.name')->toArray()) : '', // Пожарная техника ->  На ремонте -> Тип основ пожарного а/м
            $department->tech_repair ? implode($delimiter, $department->tech_repair->whereIn('vehicle.vehicle_type_id', [2,3])->pluck('vehicle.name')->toArray()) : '', // Пожарная техника ->  На ремонте -> Марка спец. пожарных а/м
        ];
    }

    private function getFirstTableRowForOrganization($formationRecord, $fields)
    {
        $delimiter = ', ';

        $result = [];
        $result[] = $formationRecord->organisationName();
        foreach ($fields as $field) {
            $result[] = $formationRecord[$field] ?? 0;
        }

        return $result;
    }


    /**
     * @param FireDepartment $department
     * @return array
     */
    private function getSecondTableRowForDepartment(FireDepartment $department)
    {
        $id = $department->id;

        $techData = array_get($this->tech, $id, []);
        $headGuard = array_get($techData, 'head_guard');

        return [
            $department->name, // Наименование пожарных подразделений

            (int)array_get($techData, 'firehose_125', 0), // Имеется на автомобилях в боевом расчете -> Рукавов -> 125мм
            (int)array_get($techData, 'firehose_75', 0), // Имеется на автомобилях в боевом расчете -> Рукавов -> 75мм
            (int)array_get($techData, 'firehose_77', 0), // Имеется на автомобилях в боевом расчете -> Рукавов -> 77мм
            (int)array_get($techData, 'firehose_51', 0), // Имеется на автомобилях в боевом расчете -> Рукавов -> 51мм

            (int)array_get($techData, 'barrel_stationary', 0), // Имеется на автомобилях в боевом расчете -> Лафетн. Ств. стац
            (int)array_get($techData, 'barrel_portable', 0), // Имеется на автомобилях в боевом расчете -> Лафетн. Ств. переносной
            (int)array_get($techData, 'pgs_600', 0), // Имеется на автомобилях в боевом расчете -> ГПС-600
            (int)array_get($techData, 'purga', 0), // Имеется на автомобилях в боевом расчете -> Пурга
            (int)array_get($techData, 'radio_station_portable', 0), // Имеется на автомобилях в боевом расчете -> Переносная радиостанция
            (int)array_get($techData, 'flashlight', 0), // Имеется на автомобилях в боевом расчете -> Электрофонари
            (int)array_get($techData, 'searchlight', 0), // Имеется на автомобилях в боевом расчете -> Прожектора
            (int)array_get($techData, 'tok', 0) . '/' . (int)array_get($techData, 'l1', 0), // Имеется на автомобилях в боевом расчете -> ТОК/Л-1
            (int)array_get($techData, 'knapsack_devices', 0), // Имеется на автомобилях в боевом расчете -> Ранцевые аппараты
            (int)array_get($techData, 'shovel', 0), // Имеется на автомобилях в боевом расчете -> Лопаты
            (int)array_get($techData, 'flapper', 0), // Имеется на автомобилях в боевом расчете -> Хлопушки
            (int)array_get($techData, 'life_rope', 0), // Имеется на автомобилях в боевом расчете -> Спасательные веревки
            (int)array_get($techData, 'foamer', 0), // Имеется на автомобилях в боевом расчете -> Пенообразователя

            (int)array_get($techData, 'foamer_reserved') . '/'. (int)array_get($techData, 'foamer_in_stock'), // Пенообразователя на складе

            (int)array_get($techData, 'damaged_hydrant_street', 0), // Количество неисправных водоисточников -> ПГ -> уличный
            (int)array_get($techData, 'damaged_hydrant_object', 0), // Количество неисправных водоисточников -> ПГ -> объектовый
            (int)array_get($techData, 'damaged_pv', 0), // Количество неисправных водоисточников -> ПВ

            (int)array_get($techData, 'active_gasoline', 0), // в боевом расчете -> бензин
            (int)array_get($techData, 'active_diesel', 0), // в боевом расчете -> солярка

            (int)array_get($techData, 'reserved_gasoline', 0), // в резерве -> бензин
            (int)array_get($techData, 'reserved_diesel', 0), // в резерве -> солярка

            (int)array_get($techData, 'generator', 0) . '/' .
            (int)array_get($techData, 'exhauster', 0) . '/' .
            (int)array_get($techData, 'girs', 0) . '/' .
            (int)array_get($techData, 'iup', 0), // 1 генератор 2 дымосос 3 гирсы
            array_get($techData, 'dvr', 0), // Видеорегистраторы

            $headGuard ? $headGuard->initials : '' // Ф.И.О Начальника караула или лица его подменяющего
        ];
    }

    /**
     * @return array
     */
    private function getSecondTableSumRow()
    {
        $sumArray = [];
        list($cols) = array_divide((new FormationTechReport())->first()->toArray());

        $this->tech->each(function (FormationTechReport $item) use (&$sumArray, $cols) {
            foreach ($cols as $key) {
                if (\is_int($item->{$key}) || \is_string($item->{$key})) {
                    if (!isset($sumArray[$key])) {
                        $sumArray[$key] = 0;
                    }
                    $sumArray[$key] += (int)$item->{$key};
                }
            }
        });

        $sumArray['dvr'] = $this->formationReport->sumDvr();

        return [
            'Итого', // Наименование пожарных подразделений

            (int)array_get($sumArray, 'firehose_125'), // Имеется на автомобилях в боевом расчете -> Рукавов -> 125мм
            (int)array_get($sumArray, 'firehose_75'), // Имеется на автомобилях в боевом расчете -> Рукавов -> 75мм
            (int)array_get($sumArray, 'firehose_77'), // Имеется на автомобилях в боевом расчете -> Рукавов -> 77мм
            (int)array_get($sumArray, 'firehose_51'), // Имеется на автомобилях в боевом расчете -> Рукавов -> 51мм

            (int)array_get($sumArray, 'barrel_stationary'), // Имеется на автомобилях в боевом расчете -> Лафетн. Ств. стац
            (int)array_get($sumArray, 'barrel_portable'), // Имеется на автомобилях в боевом расчете -> Лафетн. Ств. переносной
            (int)array_get($sumArray, 'pgs_600'), // Имеется на автомобилях в боевом расчете -> ГПС-600
            (int)array_get($sumArray, 'purga'), // Имеется на автомобилях в боевом расчете -> Пурга
            (int)array_get($sumArray, 'radio_station_portable'), // Имеется на автомобилях в боевом расчете -> Переносная радиостанция
            (int)array_get($sumArray, 'flashlight'), // Имеется на автомобилях в боевом расчете -> Электрофонари
            (int)array_get($sumArray, 'searchlight'), // Имеется на автомобилях в боевом расчете -> Прожектора
            (int)(int)array_get($sumArray, 'tok') . '/' . (int)(int)array_get($sumArray, 'l1'), // Имеется на автомобилях в боевом расчете -> ТОК/Л-1
            (int)array_get($sumArray, 'knapsack_devices'), // Имеется на автомобилях в боевом расчете -> Ранцевые аппараты
            (int)array_get($sumArray, 'shovel'), // Имеется на автомобилях в боевом расчете -> Лопаты
            (int)array_get($sumArray, 'flapper'), // Имеется на автомобилях в боевом расчете -> Хлопушки
            (int)array_get($sumArray, 'life_rope'), // Имеется на автомобилях в боевом расчете -> Спасательные веревки
            (int)array_get($sumArray, 'foamer'), // Имеется на автомобилях в боевом расчете -> Пенообразователя

            (int)array_get($sumArray, 'foamer_reserved') . '/'. (int)array_get($sumArray, 'foamer_in_stock'), // Пенообразователя на складе

            (int)array_get($sumArray, 'damaged_hydrant_street'), // Количество неисправных водоисточников -> ПГ -> уличный
            (int)array_get($sumArray, 'damaged_hydrant_object'), // Количество неисправных водоисточников -> ПГ -> объектовый
            (int)array_get($sumArray, 'damaged_pv'), // Количество неисправных водоисточников -> ПВ

            (int)array_get($sumArray, 'active_gasoline'), // в боевом расчете -> бензин
            (int)array_get($sumArray, 'active_diesel'), // в боевом расчете -> солярка

            (int)array_get($sumArray, 'reserved_gasoline'), // в резерве -> бензин
            (int)array_get($sumArray, 'reserved_diesel'), // в резерве -> солярка

            (int)(int)array_get($sumArray, 'generator') . '/' .
            (int)(int)array_get($sumArray, 'exhauster') . '/' .
            (int)(int)array_get($sumArray, 'girs') . '/' .
            (int)(int)array_get($sumArray, 'iup'), // 1 генератор 2 дымосос 3 гирсы
            array_get($sumArray, 'dvr'), // Видеорегистраторы 1/2/3

            '-' // Ф.И.О Начальника караула или лица его подменяющего
        ];
    }

    private function getOperGroupName()
    {
        $latest = FormationReport::latest()->first();
        //если отчет последний актуальный - берем текущую дату и забираем актуальную ОГ за эту дату
        //если отчет не свежий, берем его дату и по этой дате ищем ОГ за то время
        $searchDate = ($latest && $latest->id === $this->formationReport->id) ? now() : Carbon::parse($this->formationReport->created_at)->addHours(6);
        $operGroupSchedule = OperationalGroupSchedule::date($searchDate)->first();
        $operGroup = $operGroupSchedule ? $operGroupSchedule->group->name : '';

        return $operGroup;
    }


}
