<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;

class WeatherObserver
{
    use MessengerTrait;

    public function creating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"');
    }

    public function updating()
    {
        $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"');
    }
}
