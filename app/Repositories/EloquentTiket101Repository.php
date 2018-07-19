<?php

namespace App\Repositories;

use App\Repositories\Contracts\Tiket101Interface;
use App\Ticket101;

class EloquentTiket101Repository extends Repository implements Tiket101Interface
{

    protected $model;

    /**
     * ArticlesRepository constructor.
     * @param Ticket101 $ticket101
     */
    public function __construct(Ticket101 $ticket101)
    {
        $this->model($ticket101);
    }

    public function getCountDaily($from, $to)
    {
        return $this->model
//            ->whereBetween('created_at', [$from, $to])
            ->get();
    }

}