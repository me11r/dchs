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

class Report112
{
    protected $time;

    protected $tickets101;
    protected $tickets112;
    protected $fireObject;
    protected $burntObject;

    protected $dictionaries;

    public function __construct()
    {
        $this->time = time();
        $this->tickets101 = Ticket101::dailyRecords();
        $this->tickets112 = Card112::dailyRecords();
    }

    public function getReport($date = null): array
    {
        $data['fires_count'] = $this->tickets101->get()->filter(function ($event) {
            $q = TripResult::name('Пожар')->first();
            return $event->trip_result_id == ($q->id ?? 0);
        })->count();

        $data['yesterday'] = today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $data['today'] = today()->addHours(7)->format('Y-m-d H:i:s');

        $cards112 = $this->tickets112->get();
        $cards101 = $this->tickets101->get();

        $card112_roadtrips = Ticket101ServicePlan::with(['service_type'])
            ->dailyRecords()
            ->whereNotNull('card112_id')
            ->get();

        $air_rescue_report = AirRescueReport::dailyRecords();

        $callInfo = CallInfo::latest()->first();

        $data['cards112'] = $cards112;
        $data['cards101'] = $cards101;
        $data['dead_count'] = $cards101->sum('children_death_count') + $cards101->sum('people_death_count') + $cards112->sum('dead');
        $data['evacuated_count'] = $cards101->sum('evac_count') + $cards112->sum('evacuated');
        $data['poisoningCount'] = $cards101->sum('co2_poisoned_count') + $cards101->sum('ch4_poisoned_count') + $cards112->sum('poisoned');
        $data['hurt_count'] = $cards112->sum('injured_hard') + $cards101->sum('gpt_burns_count');
        $data['saved_count'] = $cards112->sum('saved') + $cards101->sum('rescued_count');
//        $data['card112_roadtrips'] = $card112_roadtrips;
        $data['mudflow_emergency_count'] = $this->tickets112->filterByServiceType('ГУ Казселезащита')->count();
        $data['roso_count'] = $this->tickets112->filterByServiceType('ГУ РОСО')->count();
        $data['cmk_count'] = $this->tickets112->filterByServiceType('ЦМК')->count();
        $data['SOME'] = Quake::latest()->first();
        $data['flooding_count'] = $this->tickets112->filterByIncidentType('Подтопления')->count();
        $data['siren_speech_tech'] = SirenSpeechTech::latest()->first();
        $data['weather_forecast'] = Weather::latest()->first();
        $data['emergency_situations'] = EmergencySituation::dailyRecords()->get();
        $data['call_info'] = $callInfo;

        $data['trip_results101'] = [];

        foreach (TripResult::all() as $tripResult) {
            $data['trip_results101'][$tripResult->name] = $this->tickets101->where('trip_result_id', $tripResult->id)->count();
        }

        $data['air_rescue_report_tech'] = $air_rescue_report->first() ? $air_rescue_report->first()->tech()->status('action')->get() : [];


        $data = array_merge($data,
            [
            'dates' => $this->getDates(),
            'gptBurnsCount' => $cards101->sum('gpt_burns_count'),
            'childrenDeathCount' => $cards101->sum('children_death_count'),
            'hospitalizedCount' => $cards101->sum('hospitalized_count'),
            'header_person' => DailyReportPerson::where('type', '=', 'header')->first(),
            'footer_first_person' => DailyReportPerson::where('type', '=', 'footer_first')->first(),
            'footer_second_person' => DailyReportPerson::where('type', '=', 'footer_second')->first()

        ]);

        return $data;
    }


    private function getDates()
    {
        return [
            'hour' => '07',
            'minutes' => '00',
            'to' => date('d.m.Y', $this->time),
            'from' => date('d.m.Y', $this->time - (60 * 60 * 24))
        ];
    }

}
