<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\Weather;

class WeatherObserver
{
    use MessengerTrait;

    public function created(Weather $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"',$url);
        }
    }

    public function updating(Weather $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/weather/{$model->id}/edit";
    }
}
