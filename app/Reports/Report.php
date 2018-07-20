<?php

namespace App\Reports;

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
            date('Y-m-d H:i:s', $this->time - 60 * 60 * 24),
            date('Y-m-d H:i:s', $this->time)
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
        return $data;
    }

    private function getDates()
    {
        return [
            'hour' => date('H', $this->time),
            'minutes' => date('i', $this->time),
            'from' => date('d.m.Y', $this->time - 60 * 60 * 24),
            'to' => date('d.m.Y', $this->time)
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