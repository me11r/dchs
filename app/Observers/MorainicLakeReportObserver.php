<?php

namespace App\Observers;

use App\Messenger\MessengerTrait;
use App\Models\MorainicLakeReport;
use App\Models\MorainicLakeSummary;

class MorainicLakeReportObserver
{
    use MessengerTrait;

    public function created(MorainicLakeSummary $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Моренные озёра"',$url);
        }
    }

    public function updating(MorainicLakeSummary $report)
    {
        if (\Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . \Auth::user()->full_username . ' произвёл изменения в разделе "Моренные озёра"',$url);
        }
    }

    private function generateUrl($model)
    {
        return "/morainic-lakes-summaries/{$model->date}/edit";
    }
}
