<?php

namespace Bidb97\CrossPosting\Providers;

use Illuminate\Support\ServiceProvider;

class CrossPostingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/cross-posting.php' => config_path('bidb97/cross-posting.php'),
        ], 'cross-posting');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}