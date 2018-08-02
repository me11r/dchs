<?php

namespace App\Providers;

use App\Repositories\Contracts\Card112RepositoryInterface;
use App\Repositories\Contracts\HydrantRepositoryInterface;
use App\Repositories\Contracts\ChatInterface;
use App\Repositories\Contracts\MessageInterface;
use App\Repositories\Contracts\NicknameInterface;
use App\Repositories\EloquentCard112Repository;
use App\Repositories\EloquentHydrantRepository;
use App\Repositories\EloquentChatRepository;
use App\Repositories\EloquentMessageRepository;
use App\Repositories\EloquentNicknameRepository;
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
        $this->registerTicket101();
        $this->registerFireObject();
        $this->registerBurntObject();
        $this->registerChat();
        $this->registerMessage();
        $this->registerNickname();
        $this->registerServiceType();
        $this->registerMudflowProtection();
        $this->registerRiver();
        $this->registerWeather();
        $this->registerQuake();
        $this->registerHydrantRepository();
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

    protected function registerCard112Repository()
    {
        $this->app->bind(
            Card112RepositoryInterface::class,
            EloquentCard112Repository::class
        );
    }

    protected function registerChat()
    {
        $this->app->bind(
            ChatInterface::class,
            EloquentChatRepository::class
        );
    }

    protected function registerMessage()
    {
        $this->app->bind(
            MessageInterface::class,
            EloquentMessageRepository::class
        );
    }

    protected function registerNickname()
    {
        $this->app->bind(
            NicknameInterface::class,
            EloquentNicknameRepository::class
        );
    }

    protected function registerServiceType()
    {
        $this->app->bind(
            'App\Repositories\Contracts\ServiceTypeInterface',
            'App\Repositories\EloquentServiceTypeRepository'
        );
    }

    protected function registerMudflowProtection()
    {
        $this->app->bind(
            'App\Repositories\Contracts\MudflowProtectionInterface',
            'App\Repositories\EloquentMudflowProtectionRepository'
        );
    }

    protected function registerRiver()
    {
        $this->app->bind(
            'App\Repositories\Contracts\RiverInterface',
            'App\Repositories\EloquentRiverRepository'
        );
    }

    protected function registerWeather()
    {
        $this->app->bind(
            'App\Repositories\Contracts\WeatherInterface',
            'App\Repositories\EloquentWeatherRepository'
        );
    }

    protected function registerQuake()
    {
        $this->app->bind(
            'App\Repositories\Contracts\QuakeInterface',
            'App\Repositories\EloquentQuakeRepository'
        );
    }

    protected function registerHydrantRepository()
    {
        $this->app->bind(
            HydrantRepositoryInterface::class,
            EloquentHydrantRepository::class
        );
    }

}
