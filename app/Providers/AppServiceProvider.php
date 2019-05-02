<?php

namespace App\Providers;

use App\AirRescueReport;
use App\FormationPersonsReport;
use App\FormationReport;
use App\FormationTechReport;
use App\Models\EmergencySituation;
use App\Models\FormationRecord;
use App\Models\Messenger\Message;
use App\Models\MorainicLakeReport;
use App\Models\MorainicLakeSummary;
use App\Models\Quake;
use App\Models\QueuedReport;
use App\Models\Weather;
use App\Observers\AirRescueReportObserver;
use App\Observers\EmergencySituationObserver;
use App\Observers\FormationPersonsReportObserver;
use App\Observers\FormationRecordObserver;
use App\Observers\FormationReportObserver;
use App\Observers\FormationTechReportObserver;
use App\Observers\Messenger\MessageObserver;
use App\Observers\MorainicLakeReportObserver;
use App\Observers\QuakeObserver;
use App\Observers\QueuedReportObserver;
use App\Observers\WeatherObserver;
use App\Translation;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        EmergencySituation::observe(EmergencySituationObserver::class);
        FormationReport::observe(FormationReportObserver::class);
        FormationPersonsReport::observe(FormationPersonsReportObserver::class);
        FormationTechReport::observe(FormationTechReportObserver::class);
        FormationRecord::observe(FormationRecordObserver::class);
        MorainicLakeSummary::observe(MorainicLakeReportObserver::class);
        Weather::observe(WeatherObserver::class);
        Quake::observe(QuakeObserver::class);
        AirRescueReport::observe(AirRescueReportObserver::class);
        QueuedReport::observe(QueuedReportObserver::class);
        Message::observe(MessageObserver::class);

        Paginator::defaultView('pagination::default');

        view()->share('protocol', $request->isSecure() ? 'https' : 'http');

        $locale = LaravelLocalization::getCurrentLocale();
        $locale = app()->getLocale();

        try {
            $translations = Translation::getByLocale($locale)->get(['key', 'value']);
        }
        catch (\Exception $exception) {
            $translations = json_encode([]);
        }

        view()->composer('*', function ($view) use ($locale, $translations) {
            $view->with([
                'language' => $locale,
                'translations' => $translations,
                'user' => Auth::user()
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../Services/TranslationHelper.php';
    }
}
