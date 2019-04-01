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
use App\Models\ServiceType;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Models\Weather;
use App\OperDutyShiftStaff;
use App\OperDutyShiftStaffItem;
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
    private $today;
    private $yesterday;

    private $tomorrow;

    protected $dictionaries;

    public function __construct($date = null)
    {
        if(!$date) {
            $this->time = time();
            $this->tickets101 = Ticket101::dailyRecords();
            $this->tickets112 = Card112::dailyRecords();
            $this->today = today()->addHours(7)->format('Y-m-d H:i:s');
            $this->yesterday = today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
            $this->tomorrow = today()->addDay(1)->addHours(7)->format('Y-m-d H:i:s');
        }
        else {
            $from = Carbon::parse($date)->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
            $to = Carbon::parse($date)->addHours(7)->format('Y-m-d H:i:s');
            $this->time = time();
            $this->tickets101 = Ticket101::dailyRecords($from, $to);
            $this->tickets112 = Card112::dailyRecords($from, $to);
            $this->today = Carbon::parse($date)->addHours(7)->format('Y-m-d H:i:s');
            $this->yesterday = Carbon::parse($date)->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
            $this->tomorrow = Carbon::parse($date)->addDay(1)->addHours(7)->format('Y-m-d H:i:s');
        }
    }

    public function getReport(): array
    {
        $data['yesterday'] = $this->yesterday;
        $data['today'] = $this->today;

        $cards112 = $this->tickets112->get();
        $cards101 = $this->tickets101
            ->whereNull('drill_type_id')
            ->get();

        $cards112_emergency = (clone $this->tickets112)->whereHas('emergency_type',function ($q) {
            $q->name('ЧС');
        })->get();

        $cards101_emergency = (clone $this->tickets101)->whereHas('emergency_type',function ($q) {
            $q->name('ЧС');
        })->get();

        $air_rescue_report = AirRescueReport::dailyRecords($this->today, $this->tomorrow);

        $callInfo = CallInfo::whereBetween('date', [$this->today, $this->tomorrow])->first();

        $data['fires_count_112'] = (clone $this->tickets112)->whereHas('emergency_type',function ($q) {
            $q->name('ЧС');
        })->whereHas('emergency_name',function ($q) {
            $q->name('Пожар');
        })->get();

        $data['fires_count_101'] = (clone $this->tickets101)->whereHas('emergency_type',function ($q) {
            $q->name('ЧС');
        })->whereHas('trip_result',function ($q) {
            $q->name('Пожар');
        })->get();

        /**/
        $data['carbonPoisoningCount'] = (clone $this->tickets101)->get()->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Отравление угарным газом%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $data['naturalPoisoningCount'] = (clone $this->tickets101)->get()->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Отравление природным газом%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $data['suicideCount'] = (clone $this->tickets101)->get()->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Покушение на самоубийство%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();
        /**/

        $data['cards112'] = $cards112;
        $data['cards101'] = $cards101;

        $data['cards112_emergency'] = $cards112_emergency;
        $data['cards101_emergency'] = $cards101_emergency;

        $data['dead_count'] = $cards101->sum('people_death_count') + $cards112->sum('dead') ."/".$cards101->sum('children_death_count');
        $data['evacuated_count'] = $cards101->sum('evac_count') + $cards112->sum('evacuated')."/".$cards101->sum('children_evacuated');
        $data['poisoningCount'] = $cards101->sum('co2_poisoned_count') + $cards101->sum('ch4_poisoned_count') + $cards112->sum('poisoned')."/0";
        $data['hurt_count'] = $cards112->sum('injured_hard') + $cards101->sum('gpt_burns_count')."/".$cards101->sum('children_death_count');
        $data['saved_count'] = $cards112->sum('saved') + $cards101->sum('rescued_count')."/".$cards101->sum('saved_children');
        $data['gptBurnsCount'] = $cards101->sum('gpt_burns_count');
        $data['children_dead_count'] = $cards101->sum('children_death_count');


        $data['emergency_dead_count'] = $cards101_emergency->sum('people_death_count') + $cards112_emergency->sum('dead') ."/".$cards101_emergency->sum('children_death_count');
        $data['emergency_evacuated_count'] = $cards101_emergency->sum('evac_count') + $cards112_emergency->sum('evacuated')."/".$cards101_emergency->sum('children_evacuated');
        $data['emergency_poisoningCount'] = $cards101_emergency->sum('co2_poisoned_count') + $cards101_emergency->sum('ch4_poisoned_count') + $cards112_emergency->sum('poisoned')."/0";
        $data['emergency_hurt_count'] = $cards112_emergency->sum('injured_hard') + $cards101_emergency->sum('gpt_burns_count')."/".$cards101_emergency->sum('children_death_count');
        $data['emergency_saved_count'] = $cards112_emergency->sum('saved') + $cards101_emergency->sum('rescued_count')."/".$cards101_emergency->sum('saved_children');


        $data['mudflow_emergency_count'] = $this->tickets112->filterByServiceType('ГУ Казселезащита')->count();
        $data['roso_count'] = $this->tickets112->filterByServiceType('ГУ РОСО')->count();
        $data['cmk_count'] = $this->tickets112->filterByServiceType('ЦМК')->count();
        $data['SOME'] = Quake::dailyRecords($this->today, $this->tomorrow)->get();
        $data['flooding_count'] = $this->tickets112->filterByIncidentType('Подтопления')->count();
        $data['siren_speech_tech'] = SirenSpeechTech::shiftRecords($this->today, $this->tomorrow)->first();
        $data['weather_forecast'] = Weather::whereBetween('date', [$this->today, $this->tomorrow])->first();
        $data['emergency_situations'] = EmergencySituation::dailyRecords()->get();
        $data['call_info'] = $callInfo;

        //РОСО,ЦМК,109,Өрт сөндіруші, РГП Казавиаспас
        $dailyServices = ServiceType::dailyServices112()->get();
        foreach ($dailyServices as $item) {
            $data['services'][$item->name] = $this->getServiceInfo($item->name);
        }

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
            'header_person' => DailyReportPerson::where('type', 'header')->where('report_type', '112_daily')->first(),
            'footer_persons' => OperDutyShiftStaff::whereHas('shifts', function ($q) {
                $q->where('date', today())
                    ->where('rank','duty_officer')
                ;
            })->get()
        ]);

        return $data;
    }

    private function getServiceInfo($service)
    {
        $emergency = EmergencySituation::dailyRecords($this->yesterday, $this->today)
            ->whereHas('user.service_type', function ($q) use ($service) {
                $q->where('name', $service);
            })->orderBy('date_time')->get();

        return $emergency;
    }


    private function getDates()
    {
        return [
            'hour' => '07',
            'minutes' => '00',
            'from' => Carbon::parse($this->yesterday)->format('d.m.Y'),
            'to' => Carbon::parse($this->today)->format('d.m.Y'),
        ];
    }

}
