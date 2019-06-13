<?php

namespace Modules\Topic\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Topic\Entities\Topic;
use Modules\Topic\Observers\TopicObserver;

class TopicServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
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
        return [];
    }

    public function boot()
    {
        Topic::observe(TopicObserver::class);
    }
}
