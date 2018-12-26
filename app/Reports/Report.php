<?php

namespace App\Reports;

use App\Analytics101Item;
use App\Chronology101;
use App\Dictionary\BurntObject;
use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use App\FireDepartmentCheck;
use App\FormationReport;
use App\FormationTechReport;
use App\LivingSectorType;
use App\Models\DailyReportPerson;
use App\Models\FireDepartmentResult;
use App\Models\FormationTechItem;
use App\Models\Ticket101\Ticket101OtherRecord;
use App\Repositories\Contracts\BurntObjectInterface;
use App\Repositories\Contracts\Ticket101Interface;
use App\Repositories\Contracts\FireObjectInterface;
use App\Ticket101Other;
use Carbon\Carbon;

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

    public function getReport($date = null): array
    {
        $firstDate = today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $secondDate = today()->addHours(7)->format('Y-m-d H:i:s');
        if($date) {
            $carbon = new Carbon($date);
            $firstDate = $carbon->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
            $secondDate = $carbon->addDay(1)->format('Y-m-d H:i:s');
            $this->time = strtotime($date);
        }
        $this->report = $this->ticket101->getDaily(
            $firstDate,
            $secondDate
        );

        /*$burntTransportCount = count($this->filterByObject(
            'burn_object_id',
            'burntObject',
            $this->dictionaries['burntObject']['transport']
        ));*/

        $burntFireCount = $this->report->filter(function ($event) {
            $q = TripResult::name('Пожар')->first();
            return $event->trip_result_id == $q->id;
        })->count();

        $livingSectorCount = $this->report->filter(function ($event) {
            return $event->living_sector_type_id !== null;
        })->count();

        $livingSectorHomeCount = $this->report->filter(function ($event) {
            $q = LivingSectorType::name('Жилой дом(квартира)')->first();
            return $event->living_sector_type_id == $q->id;
        })->count();

        $livingSectorOutdoorCount = $this->report->filter(function ($event) {
            $q = LivingSectorType::name('надворные постройки')->first();
            return $event->living_sector_type_id == $q->id;
        })->count();

        $burntTransportCount = $this->report->filter(function ($event) {
            $q = FireObject::name('Транспорт')->first();
            return $event->burn_object_id ?? 0 == $q->id;
        })->count();

        $burntOtherCount = $this->report->filter(function ($event) {
            $q = FireObject::name('Прочие объекты пожаров')->first();
            return $event->burn_object_id ?? 0 == $q->id;
        })->count();

        $burntKitchenFireCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%пища на газе%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $burntRubbishFireCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Загорание мусора%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $burntShortCircuitFireCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%КЗ эл.сетей%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $burntDryThingsFireCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%сухост%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $burnNonFiresCount = $this->report->filter(function ($event) {
            $q = TripResult::nonFires()
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $carbonPoisoningCount =  $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Отравление угарным газом%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $naturalPoisoningCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Отравление природным газом%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();

        $suicideCount = $this->report->filter(function ($event) {
            $q = TripResult::where('name', 'like', "%Покушение на самоубийство%")
                ->get()
                ->pluck('id')
                ->toArray();

            return in_array($event->trip_result_id, $q);
        })->count();


        $data = [
            'dates' => $this->getDates(),
            'allCount' => count($this->report),
            'tickets' => $this->report,

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

            'burntFireCount' => $burntFireCount,
            'livingSectorCount' => $livingSectorCount,
            'livingSectorHomeCount' => $livingSectorHomeCount,
            'livingSectorOutdoorCount' => $livingSectorOutdoorCount,
            'burntTransportCount' => $burntTransportCount,
            'burntOtherCount' => $burntOtherCount,
            'burntNonFiresCount' => $burnNonFiresCount,
            'burntKitchenFireCount' => $burntKitchenFireCount,
            'burntRubbishFireCount' => $burntRubbishFireCount,
            'burntShortCircuitFireCount' => $burntShortCircuitFireCount,
            'burntDryThingsFireCount' => $burntDryThingsFireCount,
            'poisoningCount' => ($carbonPoisoningCount + $naturalPoisoningCount),
            'carbonPoisoningCount' => $carbonPoisoningCount,
            'naturalPoisoningCount' => $naturalPoisoningCount,
            'suicideCount' => $suicideCount,
            'rescuedCount' => $this->report->sum('rescued_count'),
            'evacCount' => $this->report->sum('evac_count'),
            'gptBurnsCount' => $this->report->sum('gpt_burns_count'),
            'peopleDeathCount' => $this->report->sum('people_death_count'),
            'childrenDeathCount' => $this->report->sum('children_death_count'),
            'hospitalizedCount' => $this->report->sum('hospitalized_count'),
            'header_person' => DailyReportPerson::where('type', '=', 'header')->first(),
            'footer_first_person' => DailyReportPerson::where('type', '=', 'footer_first')->first(),
            'footer_second_person' => DailyReportPerson::where('type', '=', 'footer_second')->first()

        ];

        $data['tripResults'] = $this->tripResults();
        $data['tech'] = $this->getTech()
            ->whereHas('items', function ($q) {
                $q->where('status', 'repair');
            })->get();


        $inactive_tech_cnt = [];
        foreach ($data['tech'] as $inactive_tech) {
            foreach ($inactive_tech->items as $inactive_tech_item) {
                if ($inactive_tech_item->status == 'repair') {
                    if (in_array($inactive_tech_item->vehicle->name, $inactive_tech_cnt)) {
                        $inactive_tech_cnt[$inactive_tech_item->vehicle->name] = ++$inactive_tech_cnt[$inactive_tech_item->vehicle->name];
                    } else {
                        $inactive_tech_cnt[$inactive_tech_item->vehicle->name] = 1;
                    }
                }
            }
        }

        $data['inactive_tech_cnt'] = $inactive_tech_cnt;
        $data['arrangementYesterday'] = $this->getArrangementYesterday();
        $data['arrangementToday'] = $this->getArrangementToday();
        $data['fireDeptChecks'] = $this->getFireDeptChecks();

        return $data;
    }

    private function tripResults()
    {
        $results = [];
        foreach (TripResult::all() as $trip_result) {
            foreach ($this->report as $ticket) {
                if ($ticket->trip_result_id === $trip_result->id) {

                    /*$analytics = Analytics101Item::where('ticket101_id', $ticket->id)
                        ->where('trip_result_id', $trip_result->id)
                        ->whereHas('analytics', function ($q) {
                            $q->whereDate('date', today()->subDay());
                        })
                        ->first();*/

                    $analytics = $ticket->analytics;

                    $firstDeptArrived = $ticket->first_department_arrived();
                    $depts_out = $ticket->results()->whereNotNull('arrive_time')->get();
                    $depts_out_str = '';
                    foreach ($depts_out as $out) {
                        $depts_out_str .= "{$out->department->title}({$out->tech->department}), ";
                    }

                    if ($firstDeptArrived) {
                        $fireDeptResult = FireDepartmentResult::find($firstDeptArrived->id);
                        $firstDeptArrived->name = $fireDeptResult->department->title;
                        $firstDeptArrived->tech_dept = $fireDeptResult->tech->department;
                        $firstDeptArrived->vehicle = $fireDeptResult->tech->vehicle->name;
                    }


                    $chronology = Chronology101::where('ticket101_id', $ticket->id)
                        ->whereNotNull('event_info_arrived_id')
                        ->get();

                    $chronology_str = '';

                    if ($chronology->count()) {
                        foreach ($chronology as $chrono) {
                            $chronology_str .= "$chrono->quantity " . ($chrono->event_info_arrived->name ?? null) . ', ';
                        }
                    }

                    $service_plans_str = '';
                    foreach ($ticket->service_plans()->whereNotNull('dispatched_time')->get() as $service_plan) {
                        $service_plans_str .= $service_plan->service_type->name . ', ';
                    }


                    $max_square = $ticket->max_square ?? Ticket101OtherRecord::where('ticket101_id', $ticket->id)
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

                    /*if ($analytics && !$analytics->text) {
                        $analytics->text = view('_templates.report101-analytics', $result)->render();
                        $analytics->save();
                    }*/

                    $result['analytics'] = $analytics->text ?? null;
                    $results[$trip_result->name][] = $result;
                }
            }
        }

        return $results;
    }

    private function getArrangementYesterday()
    {
        $date = today()->addDay(-1)->format('Y-m-d');
        $formationCard101Others = Ticket101Other::whereHas('ride_type', function ($q) use ($date) {
            $q->where('name', 'Расстановка');
        })
            ->whereDate('created_at', $date)
            ->get();

        return $formationCard101Others;
    }

    private function getArrangementToday()
    {
        $date = today()->format('Y-m-d');

        $formationCard101Others = Ticket101Other::whereHas('ride_type', function ($q) use ($date) {
            $q->where('name', 'Расстановка');
        })
            ->whereDate('created_at', $date)
            ->get();

        return $formationCard101Others;
    }

    private function getTech()
    {
        $from = today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = today()->addHours(7)->format('Y-m-d H:i:s');

        return (new FormationTechReport())
            ->with('formation_tech_items')
            ->whereBetween('created_at', [$from, $to]);
    }

    private function getFireDeptChecks()
    {
        $from = today()->addDay(-1)->addHours(7)->format('Y-m-d H:i:s');
        $to = today()->addHours(7)->format('Y-m-d H:i:s');

        return (new FireDepartmentCheck())
            ->whereIn('date', [
                today()->format('Y-m-d'),
                today()->addDay(-1)->format('Y-m-d')
            ])
            ->get()
            ->filter(function ($item) use ($from, $to) {
                /** @var FireDepartmentCheck $item */
                if (Carbon::parse($to)->format('Y-m-d') === Carbon::parse($item->date)->format('Y-m-d')) {
                    return (int)Carbon::parse($item->time_end)->format('H') <= (int)Carbon::parse($to)->format('H');
                } else {
                    return (int)Carbon::parse($item->time_begin)->format('H') >= (int)Carbon::parse($from)->format('H') ;
                }
            });
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

    protected function filterByObject($nameObject, $object, $name)
    {
        $id = $this->getObjectId($object, $name);
        return $this->report->filter(function ($event) use ($nameObject, $id) {
            return $event->{$nameObject} == $id;
        });
    }

    protected function getObjectId($object, $name): int
    {
        return $this->{$object}->getByName($name)->id ?? -1;
    }

}
