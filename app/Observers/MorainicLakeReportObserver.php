<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;

class MorainicLakeReportObserver
{
    use MessengerTrait;

    public function creating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Моренные озёра"');
    }

    public function updating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Моренные озёра"');
    }
}
