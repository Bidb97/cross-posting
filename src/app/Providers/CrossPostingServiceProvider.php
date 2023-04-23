<?php

namespace Bidb97\CrossPosting\Providers;

use Bidb97\CrossPosting\Commands\Posting;
use Bidb97\CrossPosting\Http\Controllers\ShortController;
use Illuminate\Support\ServiceProvider;

class CrossPostingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $shortRoute = config('cross-posting.short_link_path') . '/{shortUri}';
        $this->app['router']->get($shortRoute, ShortController::class)->name('cross-posting:short');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'cross-posting');
        $this->publishes([__DIR__ . '/../../config/cross-posting.php' => config_path('cross-posting.php')], 'cross-posting');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Posting::class
            ]);
        }
    }
}