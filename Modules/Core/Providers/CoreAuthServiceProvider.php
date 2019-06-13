<?php

namespace Modules\Core\Providers;

use Encore\Admin\Facades\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class CoreAuthServiceProvider extends ServiceProvider
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
        \Horizon::auth(function () {
            // 是否是超级管理员
            return Admin::user()->isAdministrator();
        });
    }
}
