<?php

namespace App\Providers;

use App\AirRescueReport;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\EmergencySituation;
use App\Models\FormationRecord;
use App\Models\MorainicLakeReport;
use App\Models\Quake;
use App\Models\Weather;
use App\Observers\AirRescueReportObserver;
use App\Observers\EmergencySituationObserver;
use App\Observers\FormationPersonsReportObserver;
use App\Observers\FormationRecordObserver;
use App\Observers\FormationReportObserver;
use App\Observers\MorainicLakeReportObserver;
use App\Observers\QuakeObserver;
use App\Observers\WeatherObserver;
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
        MorainicLakeReport::observe(MorainicLakeReportObserver::class);
        Weather::observe(WeatherObserver::class);
        Quake::observe(QuakeObserver::class);
        AirRescueReport::observe(AirRescueReportObserver::class);

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
