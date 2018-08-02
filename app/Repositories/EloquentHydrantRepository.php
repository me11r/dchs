<?php

namespace App\Repositories;

use App\Models\Hydrant;
use App\Repositories\Contracts\HydrantRepositoryInterface;

class EloquentHydrantRepository extends Repository implements HydrantRepositoryInterface
{
    public function model()
    {
        return Hydrant::class;
    }
}
