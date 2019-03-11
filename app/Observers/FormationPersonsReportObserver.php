<?php

namespace App\Observers;

use App\FormationPersonsReport;
use App\Messenger\MessengerTrait;

class FormationPersonsReportObserver
{
    use MessengerTrait;

    public function creating(FormationPersonsReport $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в личном составе строевой записки');
        }
    }

    public function updating(FormationPersonsReport $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в личном составе строевой записки');
        }
    }

    private function generateUrl($model)
    {
        return "/formation/add101persons/{$model->form_id}/{$model->dept_id}";
    }
}
