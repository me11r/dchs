<?php

namespace App\Observers;

use App\FormationReport;
use App\Messenger\MessengerTrait;

class FormationReportObserver
{
    use MessengerTrait;

    public function created(FormationReport $report)
    {
        if (\Auth::user()){
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке',$url);
        }
    }

    public function updating(FormationReport $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в строевой записке',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/formation-record";
    }
}
