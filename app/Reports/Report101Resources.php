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

    public function __construct($data)
    {
        $this->data = $data;
        $depts = $data['fireDepartments']->map(function ($item) {
            return $item['id'];
        })->toArray();
        $rides = $data['reports']->map(function ($item) {
            return $item['id'];
        })->toArray();

        $this->data['fireDepartments'] = FireDepartment::whereIn('id', $depts)
            ->get();
        $this->data['reports'] = FireDepartmentResult::whereIn('id', $rides)
            ->with([
                'ticket',
                'ticket_other',
                'tech',
            ])
            ->get();
    }

    public function getReport(): array
    {
        $data['headers1'] = [
            'ПЧ',
            'Статус',
        ];

        $data['headers2'] = [
            'Отделение',
            'Кол-во выездов за период',
            'Выезда по тревоге',
            'Учения',
            'Прочие выезда',
        ];

        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];

        $data['fireDepartments'] = $this->data['fireDepartments'];

        foreach ($this->data['fireDepartments'] as $dept) {
            $data['values'][$dept->title] = [];

            foreach ($this->uniqueDepartments($dept->id) as $ride) {
                $data['values'][$dept->title][] = [
                    $ride['tech']['department'] ?? ($ride->tech->reserve) . ' резерв',
                    count($this->amountRides($dept->id, $ride['tech']['department'])),
                    count($this->amountRidesReal($dept->id, $ride['tech']['department'])),
                    count($this->amountRidesDrill($dept->id, $ride['tech']['department'])),
                    count($this->amountRidesOther($dept->id, $ride['tech']['department'])),
                ];
            }
        }

        return $data;
    }

    private function amountRidesReal($deptId, $department)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) {
            return $q['ticket'] && $q['ticket']['form_type_drill'] !== null;
        })->toArray();
    }

    private function amountRidesOther($deptId, $department)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) {
            return $q['ticket_other'];
        })->toArray();
    }

    private function amountRidesDrill($deptId, $department)
    {
        return collect($this->amountRides($deptId, $department))->filter(function ($q) {
            return $q['ticket'] && $q['ticket']['form_type_drill'] !== null;
        })->toArray();
    }

    private function amountRides($deptId, $department)
    {
        $formRides = $this->formRides()[$deptId];
        return collect($formRides)->filter(function ($q) use ($department) {
            return $q['tech']['department'] === $department;
        })->toArray();
    }

    private function uniqueDepartments($deptId)
    {
        $formRides = $this->formRides()[$deptId];
        return collect($formRides)->unique('tech.department')->toArray();
    }

    private function formRides() : array
    {
        $formRides = [];

        foreach ($this->data['fireDepartments'] as $fireDept) {
            $filtered = collect($this->data['reports'])->filter(function ($q) use ($fireDept){
                return $q->fire_department_id === $fireDept->id;
            })->toArray();

            $formRides[$fireDept->id] = $filtered;
        }

        return $formRides;
    }


}
