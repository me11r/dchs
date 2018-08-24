<?php

namespace App\Repositories;

use App\Models\EmergencySituation;
use App\Repositories\Contracts\EmergencySituationRepositoryInterface;

class EmergencySituationRepository extends Repository implements EmergencySituationRepositoryInterface
{

    public function model()
    {
        return EmergencySituation::class;
    }

}
