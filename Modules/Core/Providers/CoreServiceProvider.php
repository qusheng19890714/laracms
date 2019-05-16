<?php

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Nwidart\Modules\Module;
use Modules\Core\Entitties\Config;
use Blade;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    /**
     * 中间件
     *
     * @var array
     */
    protected $middlewares = [

        'module' => 'ModuleMiddleware',
        'theme'  => 'ThemeMiddleware',
        'front'  => 'FrontMiddleware',

    ];

    public function boot()
    {
        // 注册中间件
        $this->registerMiddleware();

        // 注册模块文件
        foreach ($this->app['modules']->getOrdered() as $module) {
            $this->registerConfig($module);
            $this->registerLanguageNamespace($module);
            $this->registerFactories($module);
        }

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //注册路由
        $this->app->register(RouteServiceProvider::class);

        //注册命令行
        $this->registerCommands();

    }

    /**
     * 注册中间件
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware()
    {
        foreach ($this->middlewares as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, "Modules\\Core\\Http\\Middleware\\{$middleware}");
        }
    }

    /**
     * 注册模块配置文件
     *
     * @param Module $module
     * @return void
     */
    protected function registerConfig(Module $module)
    {
        $moduleName = $module->getLowerName();

        // 已安装时加载自定义配置
        if ($this->app['installed'] == true && $moduleConfig = Config::get($moduleName)) {
            $this->app['config']->set($moduleName, $moduleConfig);
        }

        // 未安装的时加载模块根目录下的配置
        if ($this->app['installed'] == false && $this->app['files']->isFile($configFile = $module->getPath().'/config.php')) {
            $this->mergeConfigFrom($configFile, $moduleName);
        }

        // 注册模块Config目录下的配置
        // if ($this->app['files']->isDirectory($configPath = $module->getPath().'/Config')) {

        //     foreach ($this->app['files']->files($configPath) as $configFile) {
        //         $fileName = basename($configFile,'.php');
        //         $this->mergeConfigFrom($configFile, "$moduleName.$fileName");
        //     }
        // }
    }

    /**
     * 注册模块语言包命名空间
     *
     * @param Module $module
     * @return void
     */
    protected function registerLanguageNamespace(Module $module)
    {
        $moduleName = $module->getLowerName();
        $moduleLang = base_path("resources/lang/{$moduleName}");

        // 如果已经publish并且模块语音文件夹存在
        if (is_dir($moduleLang)) {
            return $this->loadTranslationsFrom($moduleLang, $moduleName);
        }

        return $this->loadTranslationsFrom($module->getPath() . '/Resources/lang', $moduleName);
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories($module)
    {
        if (! $this->app->environment('production')) {
            $this->app->make(Factory::class)->load($module->getPath() . '/Database/Factories');
        }
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

    /**
     * 注册命令行
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Modules\Core\Console\CreateCommand::class,
            \Modules\Core\Console\AdminControllerCommand::class,
            \Modules\Core\Console\FrontControllerCommand::class,
            \Modules\Core\Console\RebootCommand::class,
            \Modules\Core\Console\CreateThemeCommand::class,
        ]);
    }


}
