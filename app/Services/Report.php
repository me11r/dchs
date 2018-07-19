<?php

namespace App\Services;

class Report
{

    protected $time;

    public function __construct()
    {
        $this->time = time();
    }

    public function getReport(): array
    {
        $data = [
            'dates' => $this->getDates(),
            'count' => $this->getCount()
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

    private function getCount()
    {
        return [
            'count' => []
        ];
    }
}