<?php

namespace App\Observers;

use App\FormationTechReport;
use App\Messenger\MessengerTrait;

class FormationTechReportObserver
{
    use MessengerTrait;

    public function created(FormationTechReport $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в технике строевой записки',$url);
        }
    }

    public function updating(FormationTechReport $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в технике строевой записки',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/formation/add101tech/{$model->form_id}/{$model->dept_id}";
    }
}
