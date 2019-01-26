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

class Report112Emergency
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getReport(): array
    {
        $data['headers'] = [
            '№ п/п ЧС',
            'Дата и время происшествия',
            'Адрес',
            'Краткая характеристика происшествия',
            'Кол-во погибших',
            'Кол-во пострадавших',
            'Вид ЧС',
            'Примечание',
        ];

        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];


        foreach ($this->data['records'] as $index => $card112) {
            $data['values'][] = [
                ++$index, //'№ п/п ЧС',
                $card112->created_at->format('d.m.Y H:i'), //'Дата и время происшествия',
                $card112->detailed_address ? $card112->detailed_address : $card112->location, //'Адрес',
                $card112->emergency_feature, //'Краткая характеристика происшествия',
                $card112->dead, // 'Кол-во погибших',
                $card112->injured, //'Кол-во пострадавших',
                $card112->additionalIncident ? $card112->additionalIncident->name : '', //'Вид ЧС',
                '', //'Примечание',
            ];
        }

        return $data;
    }


}
