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

class Report101OtherRides
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
            'Отделение',
            'Куда',
            'Адрес',
            'Время начала',
            'Время окончания',
            'Ответственный',
        ];

//        $data['date'] = Carbon::parse($this->data['date'])->format('d.m.Y H:i');
        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];

        foreach ($this->data['records'] as $index => $record) {

            $results = FireDepartmentResult::where('ticket101_other_id',$record->id)
                ->whereNotNull('dispatch_time')
                ->get();

//            foreach ($results as $ride) {
                $data['values'][] = [
                    ++$index, //'№',
                    $record['custom_created_at'] ? Carbon::parse($record['custom_created_at'])->format('d.m.Y H:i') : '', //'Дата',
                    implode(',', $results->map(function ($item) {
                        return $item->department->title;
                    })->toArray()), //'Подразделение',
                    implode(',', $results->map(function ($item) {
                        return $item->tech->department;
                    })->toArray()), //'Отделение',
                    $record['ride_type']['name'], //'Куда',
                    $record['direction'], //'Адрес',
                    $record['time_begin'], //'Время начала',
                    $record['time_end'], // 'Время окончания',
                    $record['final_responsible_person'], // 'Ответственный',
                    "/card101-other-rides/{$record['id']}/edit", // 'id',
                ];
//            }
        }

        return $data;
    }


}
