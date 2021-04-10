<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ThirdPartyFetchingService;
use App\Services\TogglService;
use Illuminate\Contracts\Support\DeferrableProvider;

class ThirdPartyFetchingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ThirdPartyFetchingService::class, function ($app) {
            $serviceName = 'App\\Services\\' . ucfirst(strtolower($app->make('config')->get('services.time_record'))) . 'Service';
            return new $serviceName();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ThirdPartyFetchingService::class];
    }
}
