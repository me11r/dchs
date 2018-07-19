<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTicket101();
        $this->registerFireObject();
        $this->registerBurntObject();
    }


    protected function registerTicket101()
    {
        $this->app->bind(
            'App\Repositories\Contracts\Ticket101Interface',
            'App\Repositories\EloquentTicket101Repository'
        );
    }

    protected function registerFireObject()
    {
        $this->app->bind(
            'App\Repositories\Contracts\FireObjectInterface',
            'App\Repositories\EloquentFireObjectRepository'
        );
    }

    protected function registerBurntObject()
    {
        $this->app->bind(
            'App\Repositories\Contracts\BurntObjectInterface',
            'App\Repositories\EloquentBurntObjectRepository'
        );
    }


}
