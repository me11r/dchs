<?php

namespace App\Repositories;

use App\Models\River;
use App\Repositories\Contracts\RiverInterface;

class EloquentRiverRepository extends Repository implements RiverInterface
{

    public function model()
    {
        return River::class;
    }

}