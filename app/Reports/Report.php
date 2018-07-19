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

        $kitchenFireCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['kitchenFire']
        ));

        $electricFireCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['electricFire']
        ));

        $trashFireOtherCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['trashFireOther']
        ));

        $trashFireHouseCount = count($this->filterByObject(
            'fire_object_id',
            'fireObject',
            $this->dictionaries['fireObject']['trashFireHouse']
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
            'fireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['fire']
            )),
            'burntTransportCount' => $burntTransportCount,
            'burntOtherCount' => count($this->report) - $burntTransportCount,
            'notFireCount' => ($kitchenFireCount + $electricFireCount + $trashFireOtherCount + $trashFireHouseCount),
            'kitchenFireCount' => $kitchenFireCount,
            'electricFireCount' => $electricFireCount,
            'trashFireOtherCount' => $trashFireOtherCount,
            'trashFireHouseCount' => $trashFireHouseCount,
            'falseCallCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['falseCall']
            )),
            'asrCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['asr']
            )),
            'workFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['workFire']
            )),
            'peopleCallCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['peopleCall']
            )),
            'orphanFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['orphanFire']
            )),
            'otherFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['otherFire']
            )),
            'regionFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['regionFire']
            )),
            'technologyFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['technologyFire']
            )),
            'pyrophoricFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['pyrophoricFire']
            )),
            'alarmCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['alarm']
            )),
            'airFireCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['airFire']
            )),
            'suicideCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['suicide']
            )),
            'dischargesElectrCount' => count($this->filterByObject(
                'fire_object_id',
                'fireObject',
                $this->dictionaries['fireObject']['dischargesElectr']
            )),
            'poisoningCount' => ($carbonPoisoningCount + $naturalPoisoningCount),
            'carbonPoisoningCount' => $carbonPoisoningCount,
            'naturalPoisoningCount' => $naturalPoisoningCount,


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