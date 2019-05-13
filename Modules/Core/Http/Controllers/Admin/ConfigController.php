<?php

namespace Modules\Core\Http\Controllers\Admin;

use Encore\Admin\Widgets\Tab;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use Modules\Core\Entitties\Config;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Modules\Core\Traits\ModuleConfig;

//use Nwidart\Modules\Module;

class ConfigController extends Controller
{
    use HasResourceActions, ModuleConfig;

    public function index(Content $content)
    {
        $content->header(trans('core::config.index'));

        //构建tab
        $tab = new Tab();

        //构建时区与语言表单
        $local = new \Encore\Admin\Widgets\Form();

        //$local->disableReset();
        $local->action(route('core.config.local.store'));

        $local->select('lang', trans('core::config.lang.label'))->options(\Module::data('core::config.languages'))->default(config('app.locale'));
        $local->select('timezone', trans('core::config.timezone.label'))->options(\Module::data('core::config.timezones'))->default(config('app.timezone'));


        //构建系统环境表单
        $safe = new \Encore\Admin\Widgets\Form();

        //$safe->disableReset();
        $safe->action(route('core.config.safe.store'));

        //获取可运行的环境
        $envs = \Module::data('core::config.env');

        $safe->radio('env', trans('core::config.env.label'))->options($envs)->stacked()->checked([config('app.env')=>$envs[config('app.env')]]);

        //获取当前的调试状态
        $debug = config('app.debug') ? 1: 0;
        $safe->switch('debug', trans('core::config.debug.label'))->default($debug);

        //后台后缀名
        $safe->text('admin_prefix', trans('core::config.admin_prefix.label'))->default(config('admin.route.prefix'));


        //语言和时区
        $tab->add(trans('core::config.local.tab'), $local);

        //系统安全
        $tab->add(trans('core::config.safe.tab'), $safe);

        $content->body($tab);

        return $content;


    }


    public function localStore(Content $content, Request $request)
    {
        $data = $request->post();

        $validate = \Illuminate\Support\Facades\Validator::make($data,
            [
            'lang' => 'required',
            'timezone' => 'required',
        ]);

        if ($validate->fails()) {

            $error = $validate->errors()->first();
            $content->withWarning(trans('core::master.warning'), $error);


        } else {

            // 写入ENV配置
            $this->setEnv([
                'APP_LOCALE'      => $request->input('lang'),
                'APP_TIMEZONE'    => $request->input('timezone'),
            ]);
            $content->withSuccess(trans('core::master.success'), trans('core::master.saved'));

            return redirect(route('core.config.index'));

        }

    }

    public function safeStore(Content $content, Request $request)
    {
        $data = $request->post();

        $validate = \Illuminate\Support\Facades\Validator::make($data,
            [
                'env' => 'required',
                'debug' => 'required',
                'admin_prefix' => 'required',
            ]);

        if ($validate->fails()) {

            $error = $validate->errors()->first();
            $content->withWarning(trans('core::master.warning'), $error);


        } else {

            // 写入ENV配置
            $this->setEnv([
                'APP_ENV'      => $request->input('env'),
                'APP_DEBUG'    => $request->input('debug') == 'on' ? 'true' : 'false',
                'ADMIN_ROUTE_PREFIX' => $request->input('admin_prefix', 'admin'),
            ]);
            $content->withSuccess(trans('core::master.success'), trans('core::master.saved'));

            $redirectTo = url($request->input('admin_prefix', 'admin').'/core/config');

            return redirect($redirectTo);

        }

    }
}
