<?php

namespace App\Reports;

use App\Repositories\Contracts\Tiket101Interface;

class Report
{

    protected $time;

    protected $from, $to;

    protected $tiket101;

    public function __construct(Tiket101Interface $tiket101)
    {
        $this->time = time();
        $this->from = date('Y-m-d H:i:s', $this->time - 60 * 60 * 24);
        $this->to = date('Y-m-d H:i:s', $this->time);
        $this->tiket101 = $tiket101;
    }

    public function getReport(): array
    {
        $data = [
            'dates' => $this->getDates(),
            'count' => $this->tiket101->getCountDaily($this->from, $this->to)
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

}