<?php

namespace App\Providers;

use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\EmergencySituation;
use App\Models\FormationRecord;
use App\Observers\EmergencySituationObserver;
use App\Observers\FormationPersonsReportObserver;
use App\Observers\FormationRecordObserver;
use App\Observers\FormationReportObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        EmergencySituation::observe(EmergencySituationObserver::class);
        FormationReport::observe(FormationReportObserver::class);
        FormationPersonsReport::observe(FormationPersonsReportObserver::class);
        FormationTechReport::observe(FormationPersonsReportObserver::class);
        FormationRecord::observe(FormationRecordObserver::class);

        Paginator::defaultView('pagination::default');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
