<?php

namespace App\Reports;

use App\Analytics101Item;
use App\Chronology101;
use App\Dictionary\TripResult;
use App\Models\FireDepartmentResult;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Repositories\Contracts\FireObjectInterface;

class Report
{

    protected $time;
    protected $report;

    protected $ticket101;
    protected $fireObject;
    protected $burntObject;

    protected $dictionaries;

    public function __construct(
        Ticket101Interface $ticket101,
        FireObjectInterface $fireObject,
        BurntObjectInterface $burntObject)
    {
        $this->time = time();
        $this->ticket101 = $ticket101;
        $this->fireObject = $fireObject;
        $this->burntObject = $burntObject;
        $this->dictionaries = config('dictionaries');
    }

    public function getReport(): array
    {
        $this->report = $this->ticket101->getDaily(
            today()->addHours(8)->format('Y-m-d H:i:s'),

            today()->addDay()->addHours(8)->format('Y-m-d H:i:s')
        );

        $burntTransportCount = count($this->filterByObject(
            'burn_object_id',
            'burntObject',
            $this->dictionaries['burntObject']['transport']
        ));

        $carbonPoisoningCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['carbonPoisoning']
        ));

        $naturalPoisoningCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['naturalPoisoning']
        ));

        $data = [
            'dates' => $this->getDates(),
            'allCount' => count($this->report),

            // в разрезе
            'fire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['fire']
                ),
                'name' => $this->dictionaries['fireObject']['fire']
            ],
            'shortCircuit' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['shortCircuit']
                ),
                'name' => $this->dictionaries['fireObject']['shortCircuit']
            ],
            'electricFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['electricFire']
                ),
                'name' => $this->dictionaries['fireObject']['electricFire']
            ],
            'kitchenFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['kitchenFire']
                ),
                'name' => $this->dictionaries['fireObject']['kitchenFire']
            ],
            'trashFireOther' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['trashFireOther']
                ),
                'name' => $this->dictionaries['fireObject']['trashFireOther']
            ],
            'trashFireHouse' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['trashFireHouse']
                ),
                'name' => $this->dictionaries['fireObject']['trashFireHouse']
            ],
            'workFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['workFire']
                ),
                'name' => $this->dictionaries['fireObject']['workFire']
            ],
            'peopleCall' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',
                    $this->dictionaries['fireObject']['peopleCall']
                ),
                'name' => $this->dictionaries['fireObject']['peopleCall']
            ],
            'asr' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['asr']
                ),
                'name' => $this->dictionaries['fireObject']['asr']
            ],


            'falseCall' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['falseCall']
                ),
                'name' => $this->dictionaries['fireObject']['falseCall']
            ],
            'orphanFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['orphanFire']
                ),
                'name' => $this->dictionaries['fireObject']['orphanFire']
            ],
            'otherFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['otherFire']
                ),
                'name' => $this->dictionaries['fireObject']['otherFire']
            ],
            'regionFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['regionFire']
                ),
                'name' => $this->dictionaries['fireObject']['regionFire']
            ],
            'technologyFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['technologyFire']
                ),
                'name' => $this->dictionaries['fireObject']['technologyFire']
            ],
            'pyrophoricFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['pyrophoricFire']
                ),
                'name' => $this->dictionaries['fireObject']['pyrophoricFire']
            ],
            'alarm' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['alarm']
                ),
                'name' => $this->dictionaries['fireObject']['alarm']
            ],
            'airFire' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['airFire']
                ),
                'name' => $this->dictionaries['fireObject']['airFire']
            ],
            'suicide' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['suicide']
                ),
                'name' => $this->dictionaries['fireObject']['suicide']
            ],
            'dischargesElectr' => [
                'items' => $this->filterByObject(
                    'fire_object_id',
                    'fireObject',

                    $this->dictionaries['fireObject']['dischargesElectr']
                ),
                'name' => $this->dictionaries['fireObject']['dischargesElectr']
            ],

            'burntTransportCount' => $burntTransportCount,
            'burntOtherCount' => count($this->report) - $burntTransportCount,
            'poisoningCount' => ($carbonPoisoningCount + $naturalPoisoningCount),
            'carbonPoisoningCount' => $carbonPoisoningCount,
            'naturalPoisoningCount' => $naturalPoisoningCount,
            'rescuedCount' => $this->report->sum('rescued_count'),
            'evacCount' => $this->report->sum('evac_count'),
            'gptBurnsCount' => $this->report->sum('gpt_burns_count'),
            'peopleDeathCount' => $this->report->sum('people_death_count'),
            'childrenDeathCount' => $this->report->sum('children_death_count'),
            'hospitalizedCount' => $this->report->sum('hospitalized_count'),


        ];
        $data['tripResults'] = $this->tripResults();
        return $data;
    }

    private function tripResults()
    {
        $results = [];
        foreach (TripResult::all() as $trip_result) {
            foreach ($this->report as $ticket) {
                if($ticket->trip_result_id === $trip_result->id){

                    $analytics = Analytics101Item::where('ticket101_id', $ticket->id)
                        ->where('trip_result_id', $trip_result->id)->first();

                    $firstDeptArrived = $ticket->first_department_arrived();
                    $depts_out  = $ticket->results()->whereNotNull('arrive_time')->get();
                    $depts_out_str = '';
                    foreach ($depts_out as $out) {
                        $depts_out_str .= "{$out->department->title}({$out->tech->department}), ";
                    }

                    if($firstDeptArrived){
                        $fireDeptResult = FireDepartmentResult::find($firstDeptArrived->id);
                        $firstDeptArrived->name = $fireDeptResult->department->title;
                        $firstDeptArrived->tech_dept = $fireDeptResult->tech->department;
                        $firstDeptArrived->vehicle = $fireDeptResult->tech->vehicle->name;
                    }


                    $chronology = Chronology101::where('ticket101_id', $ticket->id)
                        ->whereNotNull('event_info_arrived_id')
                        ->get();
                    
                    $chronology_str = '';
                    
                    if($chronology->count()){
                        foreach ($chronology as $chrono) {
                            $chronology_str .= "$chrono->quantity " . ($chrono->event_info_arrived->name ?? null) . ', ';
                        }
                    }

                    $service_plans_str = '';
                    foreach ($ticket->service_plans()->whereNotNull('dispatched_time')->get() as $service_plan) {
                        $service_plans_str .= $service_plan->service_type->name . ', ';
                    }

                    $max_square = Ticket101OtherRecord::where('ticket101_id', $ticket->id)
                        ->max('square');

                    $result = [
                        'result_title' => $trip_result->name,
                        'date' => $ticket->created_at->format('d.m.Y H:i'),
                        'date2' => $ticket->created_at->format('d.m.Y'),
                        'city_area' => $ticket->city_area->name ?? null,
                        'address' => $ticket->location,
                        'caller_name' => $ticket->caller_name,
                        'caller_phone' => $ticket->caller_phone,
                        'pre_information' => $ticket->pre_information,
                        'depts_out' => $depts_out_str,
                        'first_dept_arrived' => $firstDeptArrived,
                        'loc_time' => $ticket->loc_time,
                        'liqv_time' => $ticket->liqv_time,
                        'id' => $ticket->id,
                        'trip_result_id' => $trip_result->id,
                        'chronology_str' => $chronology_str,
                        'square_max' => $max_square,
                        'kui' => $ticket->kui,
                        'ticket' => $ticket,
                        'service_plans_str' => $service_plans_str,
                    ];

                    if($analytics && !$analytics->text){
                        $analytics->text = view('_templates.report101-analytics', $result)->render();
                        $analytics->save();
                    }

                    $result['analytics'] = $analytics->text ?? view('_templates.report101-analytics', $result)->render() ?? null;

                    $results[$trip_result->name][] = $result;
                }
            }
        }

        return $results;
    }

    private function getDates()
    {
        return [
            'hour' => '08',
            'minutes' => '00',
            'to' => date('d.m.Y', $this->time + 60 * 60 * 24),
            'from' => date('d.m.Y', $this->time)
        ];
    }

    protected function filterByObject($nameObject, $object, $name)
    {
        $id = $this->getObjectId($object, $name);
        return $this->report->filter(function ($event) use ($nameObject, $id) {
            return $event->{$nameObject} == $id;
        });
    }

    protected function getObjectId($object, $name): int
    {
        return $this->{$object}->getByName($name)->id ?? 0;
    }

}