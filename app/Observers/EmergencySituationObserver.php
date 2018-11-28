<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\EmergencySituation;

class EmergencySituationObserver
{
    use MessengerTrait;

    public function creating(EmergencySituation $model)
    {
        if (\Auth::user()) {
            $model->user_id = \Auth::user()->id;
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации');
        }
    }

    public function updating()
    {
        if (\Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации');
        }
    }
}
