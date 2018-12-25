<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use Illuminate\Support\Facades\Auth;

class AirRescueReportObserver
{
    use MessengerTrait;

    public function creating()
    {
        if (Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . Auth::user()->full_username . ' произвёл изменения в строевой записке');
        }
    }

    public function updating()
    {
        if (Auth::user()) {
            $this->sendMessageAboutFormationAction('Пользователь ' . Auth::user()->full_username . ' произвёл изменения в строевой записке');
        }
    }
}
