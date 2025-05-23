<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Assistant\ChatAssistant;
use App\Livewire\Assistant\CustomChatAssistant;

class AssistantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Livewire components
        // Livewire::component('assistant.chat-assistant', ChatAssistant::class); // Original component
        Livewire::component('assistant.chat-assistant', CustomChatAssistant::class); // Custom implementation

        // Share the assistant components with all views
        view()->composer('*', function ($view) {
            $view->with('showAssistant', true);
        });
    }
}
