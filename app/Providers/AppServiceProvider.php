<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ThirdPartyFetchingService;
use App\Services\TogglService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $config = $this->app->make('config')->get('service.time_record');
        $this->app->singleton(ThirdPartyFetchingService::class, TogglService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
