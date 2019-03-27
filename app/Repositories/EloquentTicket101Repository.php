<?php

namespace App\Repositories;

use App\Repositories\Contracts\Ticket101Interface;
use App\Ticket101;

class EloquentTicket101Repository extends Repository implements Ticket101Interface
{

    public function model()
    {
        return Ticket101::class;
    }

    public function getDaily($from, $to)
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->whereNull('drill_type_id')
            ->with('city_area', 'departments', 'trip_result', 'liquidation_method')
            ->get();
    }
}