<?php

namespace App\Providers;

use App\Models\EmergencySituation;
use App\Observers\EmergencySituationObserver;
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
