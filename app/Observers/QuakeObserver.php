<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\Quake;

class QuakeObserver
{
    use MessengerTrait;

    public function created(Quake $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"',$url);
        }
    }

    public function updating(Quake $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Информация"',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/quakes/{$model->id}/edit";
    }
}
