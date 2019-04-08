<?php

namespace App\Reports;

use App\AirRescueReport;
use App\Analytics101Item;
use App\CallInfo;
use App\Chronology101;
use App\Dictionary\BurntObject;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
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

class Report101DrillRides
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getReport(): array
    {
        $data['headers'] = [
            '№',
            'Дата',
            'Подразделение',
            'Тип',
            'Отделение',
            'Наименование',
            'Адрес',
            'Время начала',
            'Время окончания',
            'ФИО',
            'ПровПГ',
            'НеиспПГ',
            'ПровПВ',
            'НеиспПВ',
            'ОП',
            'ОК',
        ];

        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];

        foreach ($this->data['records'] as $index => $record) {

            $record['departments'] = !is_array($record['departments']) ? $record['departments']->toArray() : $record['departments'];
            $data['values'][] = [
                ++$index, //'№',
                $record['date'], //'Дата',
                implode(',', array_map(function ($item) {
                    return $item['name'];
                },$record['fire_departments'])), //'Подразделение',
                $record['type'], //'Дата',
                implode(',', array_map(function ($item) {return $item['name'];},$record['departments'])),
                $record['name'], //'Куда',
                $record['location'], //'Адрес',
                $record['time_begin'], //'Время начала',
                $record['time_end'], // 'Время окончания',
                $record['responsible_person'], // 'Ответственный',
                $record['checked_pg_total'], // 'ПровПГ',
                $record['out_pg_total'], // 'НеиспПГ',
                $record['checked_pv_total'], // 'ПровПВ',
                $record['out_pv_total'], // 'НеиспПВ',
                $record['corrected_op_total'], // 'ОП',
                $record['corrected_ok_total'], // 'ОК',
                $record['href'],
            ];
        }

        return $data;
    }


}
