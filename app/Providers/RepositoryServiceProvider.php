<?php

namespace App\Providers;

use App\Repositories\Contracts\Card112RepositoryInterface;
use App\Repositories\EloquentCard112Repository;
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
        $this->registerCard112Repository();
    }

    protected function registerCard112Repository()
    {
        $this->app->bind(
            Card112RepositoryInterface::class,
            EloquentCard112Repository::class
        );
    }
}
