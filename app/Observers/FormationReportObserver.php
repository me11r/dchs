<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;

class FormationReportObserver
{
    use MessengerTrait;

    public function creating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке');
    }

    public function updating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке');
    }
}
