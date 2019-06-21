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

class Report101ObjectClass
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getReport(): array
    {
        $data = $this->data;
        $data['months'] = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
        $data['drillTypes'] = ["ПТЗ", "ПТУ"];
        $values = [];

        /*headers*/
        foreach ($data['drillTypes'] as $drillType) {
            $values[$drillType]['headers'][] = "Классификация объектов";
            foreach ($data['months'] as $month) {
                $values[$drillType]['headers'][] = $month;
            }
            $values[$drillType]['headers'][] = 'Итого';
        }

        foreach ($data['drillTypes'] as $drillType) {
            foreach ($data['object_classes'] as $object_class) {
                $tempArr[] = $object_class['name'];

                foreach ($this->data['records'][$drillType][$object_class['name']] as $item) {
                    $tempArr[] = $item;
                }

                $tempArr[] = $this->data['counts'][$drillType]['per_object'][$object_class['name']];
                $values[$drillType]['values'][] = $tempArr;
                $tempArr = [];
            }
            $tempArr = [];
            $tempArr[] = 'Итого';
            foreach ($this->data['counts'][$drillType]['per_month'] as $total) {
                $tempArr[] = $total;
            }

            $values[$drillType]['values'][] = $tempArr;
            $tempArr = [];
        }

        $data['values'] = $values;

        return $data;
    }
}
