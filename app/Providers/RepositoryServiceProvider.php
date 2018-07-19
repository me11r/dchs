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
        $this->registerTiket101();
    }


    protected function registerTiket101()
    {
        $this->app->bind(
            'App\Repositories\Contracts\Tiket101Interface',
            'App\Repositories\EloquentTiket101Repository'
        );
    }

}
