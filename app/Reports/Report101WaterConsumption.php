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

class Report101WaterConsumption
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
            '№ карточки',
            'Дата',
            'Первым стволом (стволами от емкости автоцистерны)',
            'С установкой пож.автомобилей на водоисточники, ПГ',
            'От емкости нескольких автоцистерн (подвозом воды)',
            'Пенные стволы',
            'Подручными средствами',
            'До прибытия',
            'Время тушения',
        ];

        $data['dateFrom'] = Carbon::parse($this->data['dateFrom'])->format('d.m.Y');
        $data['dateTo'] = Carbon::parse($this->data['dateTo'])->format('d.m.Y');

        $data['values'] = [];

        foreach ($this->data['records'] as $index => $record) {

            $data['values'][] = [
                ++$index,
                $record['id'],
                $record['date'],
                $record['liquidation_method_id'][1],
                $record['liquidation_method_id'][2],
                $record['liquidation_method_id'][3],
                $record['liquidation_method_id'][9],
                $record['liquidation_method_id'][4],
                $record['liquidation_method_id'][5],
                $record['time'],
            ];
        }

        return $data;
    }


}
