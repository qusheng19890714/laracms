<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entitties\Config;
use Route;
use Cache;
use Artisan;
use Module;
use Exception;
use PDOException;
use DB;

class InstallController extends Controller
{
    /**
     * app 实例
     * @var
     */
    protected $app;

    /**
     * @var 本地语言
     */
    protected $locale;

    /**
     * @var view实例
     */
    protected $view;

    /**
     * view数据
     * @var array
     */
    protected $viewData = [];

    public function __construct()
    {
        //实例
        $this->app = app();

        //view实例
        $this->view = $this->app->make('view');

        //wizard
        $this->wizard = ['welcome', 'check', 'config', 'database', 'modules', 'finished'];

        //获取当前动作指针
        $this->current = array_search(Route::getCurrentRoute()->getActionMethod(), $this->wizard);

        //获取前一个动作
        $this->prev    = ($this->current > 0) ? $this->wizard[$this->current - 1] : null;

        //获取下一个动作
        $this->next = ($this->current < (count($this->wizard) -1)) ? $this->wizard[$this->current+1] : null;

        // 获取当前动作
        $this->current = $this->wizard[$this->current];
    }

    /**
     * 传入参数, 支持链式
     *
     * @param  string|array $key 参数名
     * @param  mixed $value 参数值
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->viewData[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     *
     * @access protected
     * @param string $name 模板显示变量
     * @return mixed
     */
    public function __get($key)
    {
        return $this->viewData[$key];
    }

    /**
     * 显示View
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        // 默认模板
        $view = empty($view) ? "install.{$this->current}" : $view;

        // 转换模板数据
        $data = ($data instanceof Arrayable) ? $data->toArray() : $data;

        // 合并模板数据
        $data = array_merge($this->viewData, $data);

        // 生成 view
        return $this->view->make($view, $data, $mergeData);
    }

    /**
     * 消息提示
     *
     * @param  array  $msg 消息内容
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function message(array $msg)
    {
        return response()->json($msg);
    }

    /**
     * 消息提示：success
     *
     * @param  mixed  $msg  消息内容
     * @param  string  $url  跳转路径
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function success($msg, $url='', $time=2)
    {
        $msg = is_array($msg) ? $msg : ['content'=>$msg];

        $msg = array_merge($msg, [
            'state' => true,
            'type'  => 'success',
            'url'   => $url,
            'time'  => $time
        ]);

        return $this->message($msg);
    }

    /**
     * 消息提示：error
     *
     * @param  mixed  $msg  消息内容
     * @param  integer $time 跳转或者消息提示时间
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function error($msg, $time=5)
    {
        $msg = is_array($msg) ? $msg : ['content'=>$msg];

        $msg = array_merge($msg, [
            'state' => false,
            'type'  => 'error',
            'time'  => $time
        ]);

        return $this->message($msg);
    }

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome(Request $request)
    {
        // 重新生成 generate
        Artisan::call('key:generate');

        // 设置当前域名
        Artisan::call('env:set', [
            'key' => 'APP_URL',
            'value' => $request->root()
        ]);

        // 清理缓存
        //Artisan::call('preview:clear');
        //Artisan::call('thumbnail:clear');
        Artisan::call('reboot');

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $check = true;
        $error = [];

        // php version must > 7.0.0
        if (version_compare(PHP_VERSION, config('install.php_version','7.0.0'), '<')) {
            $check = false;
            $error['php_version'] = [config('install.php_version','7.0.0'), PHP_VERSION];
        }

        // php extensions
        foreach(config('install.php_extensions', []) as $extension) {
            if(!extension_loaded($extension)) {
                $check = false;
                $error['php_extensions'][$extension] = false;
            }
        }
        // if function doesn't exist we can't check apache modules
        if(function_exists('apache_get_modules') && $apache_get_modules = apache_get_modules()) {

            foreach (config('install.apache', []) as $requirement) {
                if(!in_array($requirement, apache_get_modules())) {
                    $check = false;
                    $error['apache'][$requirement] = false;
                }
            }
        }

        foreach(config('install.permissions', []) as $path => $permission) {

            $realpath = base_path($path);

            if ($this->app['files']->exists($realpath)) {
                $server_permission = substr(sprintf('%o', fileperms($realpath)), -4);
                if($server_permission < $permission) {
                    $check = false;
                    $error['permissions'][$path] = [$permission, $server_permission];
                }
            } else {
                $check = false;
                $error['permissions'][$path] = [$permission, null];
            }
        }

        $this->check = $check;
        $this->error = $error;

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function config(ConfigRepository $config, Request $request)
    {
        if ($request->isMethod('POST')) {

            //保存填写信息到session
            $request->session()->put('install.site', $request->input('site'));

            // 测试数据库是否能正常连接
            $env    = $request->input('env');

            // 数据库配置
            $config['database.default']                                            = $env['DB_CONNECTION'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.host']     = $env['DB_HOST'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.port']     = $env['DB_PORT'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.database'] = $env['DB_DATABASE'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.username'] = $env['DB_USERNAME'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.password'] = $env['DB_PASSWORD'];
            $config['database.connections.' . $env['DB_CONNECTION'] . '.prefix']   = $env['DB_PREFIX'];

            app(DatabaseManager::class)->purge($env['DB_CONNECTION']);
            app(ConnectionFactory::class)->make($config['database.connections.' . $env['DB_CONNECTION']], $env['DB_CONNECTION']);

            try {
                app('db')->reconnect()->getPdo();

                foreach ($env as $key => $value) {
                    Artisan::call('env:set',['key' => $key, 'value'=>$value]);
                }

                return $this->success('success', route("install.{$this->next}"));
            } catch (PDOException $e) {
                return $this->error($e->getMessage());
            }
        }

        return $this->view();
    }


    /**
     * Display the database check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function database(Request $request)
    {
        if ($request->isMethod('POST')) {

            $action = $request->input('action');

            // 覆盖安装
            if ($action == 'override') {
                try {
                    Artisan::call('migrate:fresh');
                    return $this->success('success', route("install.{$this->next}"));
                } catch (Exception $e) {
                    return $this->error($e->getMessage());
                }
            }

            // 初始安装
            if ($action == 'init') {
                try {
                    Artisan::call('migrate');
                    Artisan::call('admin:install');
                    return $this->success('success', route("install.{$this->next}"));
                } catch (Exception $e) {
                    return $this->error($e->getMessage());
                }
            }


            // 插入站点设置
            Config::set('site', $request->session()->get('install.site'));
        }

        $this->installed = false;

        // 判断是否已经安装，如果已经安装，进入提示覆盖页面
        if (Schema::hasTable('migrations') || Schema::hasTable('users')) {
            $this->installed = true;
        }

        // 是否安装测试数据

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function modules(Request $request)
    {
        if ($request->isMethod('POST')) {
            // 安装模块
            if ($name  = $request->input('name')) {
                Module::findOrFail($name)->install(true);
                return $this->success($name.' install success');
            }
        }

        $this->modules = module();

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function finished(Request $request)
    {
        // 完成安装以前，写入网站设置
        if ($this->app['installed'] == false) {

            // 设置为已安装
            Artisan::call('env:set',['key' => 'APP_INSTALLED', 'value'=>'true']);

            //发布主题
            //Artisan::call('theme:publish');

            // 重启系统
            Artisan::call('reboot');
        }

        return $this->view();
    }
}
