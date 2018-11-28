<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;

class QuakeObserver
{
    use MessengerTrait;

    public function creating()
    {
        if (\Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"');
        }
    }

    public function updating()
    {
        if (\Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"');
        }
    }
}
