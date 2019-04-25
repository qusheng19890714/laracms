<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;


abstract class CoreRouteServiceProvider extends ServiceProvider
{
    /**
     * 根命名空间，必须通过模块中的路由子类覆盖
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * 定义路由绑定
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // 注册闭包命令行
        $consoleRouteFile = $this->getConsoleRouteFile();

        if ($this->app->runningInConsole() && $consoleRouteFile && file_exists($consoleRouteFile)) {
            require $consoleRouteFile;
        }

    }

    /**
     * 前端路由文件地址
     *
     * @return mixed
     */
    protected function getFrontRouteFile()
    {
        return false;
    }

    /**
     * 后端路由文件地址
     *
     * @return mixed
     */
    protected function getAdminRouteFile()
    {
        return false;
    }

    /**
     * Api路由文件地址
     *
     * @return mixed
     */
    protected function getApiRouteFile()
    {
        return false;
    }

    /**
     * 闭包命令行文件地址
     * @return mixed
     */
    public function getConsoleRouteFile()
    {
        return false;
    }

    /**
     * 定义路由
     *
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes($router);
        $this->mapFrontRoutes($router);
        $this->mapAdminRoutes($router);
    }

    /**
     * 定义api路由
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapApiRoutes(Router $router)
    {
        $apiRouteFile = $this->getApiRouteFile();

        if ($apiRouteFile && file_exists($apiRouteFile)) {
            $router->group([
                'type'       => 'api',
                'namespace'  => $this->namespace.'\Api',
                'prefix'     => 'api',
                'middleware' => [],
            ], function (Router $router) use ($apiRouteFile) {
                require $apiRouteFile;
            });
        }
    }

    /**
     * 定义前端路由
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapFrontRoutes(Router $router)
    {
        $frontRouteFile = $this->getFrontRouteFile();

        if ($frontRouteFile && file_exists($frontRouteFile)) {
            $router->group([
                'type'       => 'front',
                'namespace'  => $this->namespace,
                'middleware' => ['web','module','front','locale','theme'],
            ], function (Router $router) use ($frontRouteFile) {
                require $frontRouteFile;
            });
        }
    }

    /**
     * 定义后端路由
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapAdminRoutes(Router $router)
    {
        $adminRouteFile = $this->getAdminRouteFile();

        if ($adminRouteFile && file_exists($adminRouteFile)) {

            $router->group([
                'type'       => 'admin',
                'namespace'  => $this->namespace.'\Admin',
                'prefix'     => env('ADMIN_ROUTE_PREFIX', 'admin'),
                'middleware' => array_merge(config('admin.route.middleware'), ['module']),
            ], function (Router $router) use ($adminRouteFile) {
                require $adminRouteFile;
            });
        }
    }






}
