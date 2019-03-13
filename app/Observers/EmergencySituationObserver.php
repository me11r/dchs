<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\EmergencySituation;

class EmergencySituationObserver
{
    use MessengerTrait;

    public function created(EmergencySituation $model)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($model);
            $model->user_id = \Auth::user()->id;
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации',$url);
        }
    }

    public function updating(EmergencySituation $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в оперативной информации',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/emergency-situation/{$model->id}/edit";
    }
}
