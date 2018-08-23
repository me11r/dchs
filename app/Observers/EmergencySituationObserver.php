<?php

namespace App\Observers;

use App\Models\EmergencySituation;

class EmergencySituationObserver
{
    public function creating(EmergencySituation $model)
    {
        $model->user_id = \Auth::user()->id;
    }

}
