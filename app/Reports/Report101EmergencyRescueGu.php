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

class Report101EmergencyRescueGu
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getReport(): array
    {
        $data['headers1'] = [
            'Дата',
            'Кол-во выездов по тревоге',
            'Из них на АСР',
            'Кол-во ложных выездов',
            'Численность привлеченного  л/c (человек)',
            'Кол-во привлеченной техники (единиц)',
        ];

        $data['headers2'] = [
            'В ходе аварийно-спасательных работ:',
        ];

        $data['headers3'] = [
            'Кол-во освобожденных а/м призаносах',
            'Кол-во спасенных человек',
            'В том числе детей',
            'Извлечено тел',
            'В том числе детей',
            'Оказана мед. помощь',
            'В том числе детям',
            'Кол-во эвакуированных',
            'В том числе детей',
        ];

        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = ["{$data['dateFrom']} - {$data['dateTo']}"];
        $data['values'] = array_merge($data['values'], $this->data['record']);

        $values = [];

        foreach ($data['values'] as $value) {
            $values[] = $value;
        }

        $data['values'] = $values;

        return $data;
    }
}
