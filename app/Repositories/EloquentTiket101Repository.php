<?php

namespace App\Repositories;

use App\Repositories\Contracts\Tiket101Interface;
use App\Ticket101;

class EloquentTiket101Repository extends Repository implements Tiket101Interface
{

    protected $model;

    public function model()
    {
        $this->model = Ticket101::class;
    }

    public function getCountDaily()
    {
        $this->model->count();
    }

}