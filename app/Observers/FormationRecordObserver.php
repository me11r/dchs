<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\FormationRecord;

class FormationRecordObserver
{
    use MessengerTrait;

    public function created(FormationRecord $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке', $url);
        }
    }

    public function updating(FormationRecord $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке', $url);
        }
    }

    private function generateUrl($model)
    {
        return "/formation-record/{$model->id}/edit";
    }
}
