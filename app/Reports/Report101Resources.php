<?php

namespace App\Reports;

use App\AirRescueReport;
use App\Analytics101Item;
use App\CallInfo;
use App\Chronology101;
use App\Dictionary\BurntObject;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\FireDepartment;
use App\FireDepartmentCheck;
use App\FormationReport;
use App\FormationTechReport;
use App\LivingSectorType;
use App\Models\Card112\Card112;
use App\Models\DailyReportPerson;
use App\Models\EmergencySituation;
use App\Models\FireDepartmentResult;
use App\Models\FormationTechItem;
use App\Models\Quake;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Weather;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Repositories\Contracts\FireObjectInterface;
use App\SirenSpeechTech;
use App\Ticket101;
use App\Ticket101Other;
use App\Ticket101ServicePlan;
use Carbon\Carbon;

class Report101Resources
{
    private $data;
    private $rides;

    public function __construct($data)
    {
        $this->data = $data;
        $this->formRides();
    }

    public function getReport(): array
    {
        $data['headers1'] = [
            'ПЧ',
            'Статус',
        ];

        $data['headers2'] = [
            'Количество выездов по тревоге'
        ];

        $data['headers3'] = [
            'Отделение',
            'Общее количество выездов по тревоге',
            'Пожары',
            'Проведение аварийно-спасательных работ',
            'Ложные/Бдительность населения',
            'Срабатывание сигнализации',
            'Область',
            'Случаи горения, не подлежащие учету как пожары',
            'Практические выезда (ПТЗ,ПТУ,ТСУ,РКШУ,Учения, ТДК)',
            'Корректировки',
            'Прочие Выезда',
        ];


        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];

        $data['fireDepartments'] = $this->data['fireDepartments'];

        foreach ($this->data['fireDepartments'] as $dept) {
            $data['values'][$dept->title] = [];

            foreach ($this->uniqueDepartments($dept->id) as $ride) {

                //Общее колво карточек 101 по всем результатам выезда
                $count = count($this->amountReal($dept->id, $ride['tech']['department']));
                //Колво Карточек с  результатом выезда ПОЖАР
                $countFire = count($this->amountTrip($dept->id, $ride['tech']['department'], [1]));
                //Колво Карточек с  результатом выезда АСР
                $countAsr = count($this->amountTrip($dept->id, $ride['tech']['department'], [3]));
                //Сумма колва карточек 101 с результатами выезда Ложный и Бдительность Населения
                $countPeople = count($this->amountTrip($dept->id, $ride['tech']['department'], [2,5]));
                //Колво Карточек с  результатом выезда Срабатывание сигнал
                $countSignal = count($this->amountTrip($dept->id, $ride['tech']['department'], [17]));
                //Колво Карточек с  результатом выезда Область
                $countRegion = count($this->amountTrip($dept->id, $ride['tech']['department'], [12]));


                $data['values'][$dept->title][] = [
                    $ride['tech']['department'] ?? ($ride->tech->reserve) . ' резерв',

                    $count,
                    $countFire,
                    $countAsr,
                    $countPeople,
                    $countSignal,
                    $countRegion,
                    //Сумма всех карточек 101 с остальными результатами выезда.
                    $count - ($countFire + $countAsr + $countPeople + $countSignal + $countRegion),

                    //Колво карточек учения по ТИПУ УЧЕНИЯ (ПТЗ,ПТУ,ТСУ,РКШУ,Учения, ТДК)
                    count($this->amountDrill($dept->id, $ride['tech']['department'], [1,2,3,4,5,6])),
                    //Колво карточек учения 101 с Типом учения Корректировка
                    count($this->amountDrill($dept->id, $ride['tech']['department'], [7])),
                    //Колво карточек прочие выезда 101
                    count($this->amountRidesOther($dept->id, $ride['tech']['department']))
                ];
            }
        }

        return $data;
    }

    private function amountTrip($deptId, $department, $trip)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) use ($trip) {
            return $q['ticket'] && in_array($q['ticket']['trip_result_id'], $trip);
        })->toArray();
    }

    private function amountDrill($deptId, $department, $drill)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) use ($drill) {
            return $q['ticket'] && in_array($q['ticket']['drill_type_id'], $drill);
        })->toArray();
    }

    private function amountReal($deptId, $department)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) {
            return $q['ticket'] && !$q['ticket']['drill_type_id'];
        })->toArray();
    }

    private function amountRidesOther($deptId, $department)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) {
            return $q['ticket_other'];
        })->toArray();
    }

    private function amountRides($deptId, $department)
    {
        $formRides = $this->rides[$deptId];
        return collect($formRides)->filter(function ($q) use ($department) {
            return $q['tech']['department'] === $department;
        })->toArray();
    }

    private function uniqueDepartments($deptId)
    {
        $formRides = $this->rides[$deptId];
        return collect($formRides)->unique('tech.department')->toArray();
    }

    private function formRides() : array
    {
        $this->rides = [];

        foreach ($this->data['fireDepartments'] as $fireDept) {
            $filtered = collect($this->data['reports'])->filter(function ($q) use ($fireDept){
                return $q->fire_department_id === $fireDept->id;
            })->toArray();
            $this->rides[$fireDept->id] = $filtered;
        }

        return $this->rides;
    }


}
