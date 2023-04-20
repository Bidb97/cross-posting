<?php

namespace Bidb97\CrossPosting\Providers;

use Bidb97\CrossPosting\Http\Controllers\ShortController;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Routing\Router;

class CrossPostingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $shortRoute = config('cross-posting.short_link_path') . '/{short_uri}';
        $this->app['router']->get($shortRoute, [ShortController::class, 'show'])->name('cross-posting:short.show');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'cross-posting');
        $this->publishes([__DIR__ . '/../../config/cross-posting.php' => config_path('cross-posting.php')], 'cross-posting');
    }
}