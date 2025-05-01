<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Agents\EventureAssistant;

class NeuronAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(EventureAssistant::class, function ($app) {
            return EventureAssistant::make();
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
