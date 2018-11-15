<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;

class FormationPersonsReportObserver
{
    use MessengerTrait;

    public function creating()
    {
        if (\Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в личном составе строевой записки');
        }
    }

    public function updating()
    {
        if (\Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в личном составе строевой записки');
        }
    }
}
