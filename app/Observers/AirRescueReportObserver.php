<?php

namespace App\Observers;

use App\AirRescueReport;
use App\Messenger\MessengerTrait;
use Illuminate\Support\Facades\Auth;

class AirRescueReportObserver
{
    use MessengerTrait;

    public function created(AirRescueReport $report)
    {
        if (Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . Auth::user()->full_username . ' произвёл изменения в строевой записке', $url);
        }
    }

    public function updating(AirRescueReport $report)
    {
        if (Auth::user()) {
            $url = $this->generateUrl($report);
            $this->sendMessageAboutFormationAction('Пользователь ' . Auth::user()->full_username . ' произвёл изменения в строевой записке', $url);
        }
    }

    private function generateUrl($model)
    {
        return "/formation/air-rescue/{$model->id}/edit";
    }
}
