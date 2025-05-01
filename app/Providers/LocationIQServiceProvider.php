<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\LocationIQHelper;

class LocationIQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LocationIQHelper::class, function ($app) {
            return new LocationIQHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
