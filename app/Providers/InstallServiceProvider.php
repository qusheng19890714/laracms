<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('installed', function(){

            return true === $this->app['config']->get('app.installed', false);
        });

        //检查是否安装
        $this->checkInstalled();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function checkInstalled()
    {
        if ($this->app['installed'] == false) {

            if ($this->app['request']->is('install', 'install/*', '_debugbar/*')) {

                // 加载安装路由
                $this->loadRoutesFrom(base_path('/routes/install.php'));

            }else {

                $env = base_path('.env');

                //如果没有env文件, 将env.example复制为env
                if (!$this->app['files']->isFile($env)) {

                    $this->app['files']->copy(base_path('.env.example'), $env);
                }

                // 强制进入安装
                header('Location:'.url('install'));

                exit('The cms has not been installed. Please use the installer to install it');
            }
        }
    }
}
