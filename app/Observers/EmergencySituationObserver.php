<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\EmergencySituation;

class EmergencySituationObserver
{
    use MessengerTrait;

    public function creating(EmergencySituation $model)
    {
        $model->user_id = \Auth::user()->id;
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации');
    }

    public function updating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации');
    }
}
