<?php

namespace App\Repositories;

use App\Models\Quake;
use App\Repositories\Contracts\QuakeInterface;

class EloquentQuakeRepository extends Repository implements QuakeInterface
{

    public function model()
    {
        return Quake::class;
    }

}