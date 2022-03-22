<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Managers\ThirdPartyServiceManager;
use App\Services\TogglService;

class ThirdPartyFetchingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('third_party_service', function ($app) {
            return new ThirdPartyServiceManager($app);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $manager = $this->app['third_party_service'];
        $manager->extend('toggl', function ($app) {
            return new TogglService();
        });
    }
}
