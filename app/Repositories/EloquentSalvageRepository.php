<?php

namespace App\Repositories;

use App\Models\Salvage;
use App\Repositories\Contracts\SalvageInterface;

class EloquentSalvageRepository extends Repository implements SalvageInterface
{

    public function model()
    {
        return Salvage::class;
    }
}