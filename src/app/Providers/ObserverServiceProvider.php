<?php

namespace Bidb97\CrossPosting\Providers;

use Bidb97\CrossPosting\Contracts\CrossPosting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Bidb97\CrossPosting\Observers\ClientModelObserver;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(['eloquent.creating: *', 'eloquent.updating: *'], function ($event, $context) {

            $model = $context[0];

            if (!($model instanceof CrossPosting)) {
                return;
            }

            $this->app->bind(ClientModelObserver::class, function () use ($model) {
                return new ClientModelObserver($model);
            });

            $model::observe(ClientModelObserver::class);
        });
    }
}