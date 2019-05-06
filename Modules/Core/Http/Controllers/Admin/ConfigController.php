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

        $local->select('lang', trans('core::config.lang.label'))->options(route('core.config.lang'));
        $local->select('timezone', trans('core::config.timezone.label'))->options(route('core.config.timezone'));



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


        //语言和时区
        $tab->add(trans('core::config.local.tab'), $local);

        //系统安全
        $tab->add(trans('core::config.safe.tab'), $safe);

        $content->body($tab);

        return $content;


    }


    public function lang()
    {
        $languages = \Module::data('core::config.languages');

        foreach ($languages as $k => $language)
        {
            if ($language['id'] == config('app.locale')) {

                $languages[$k]['selected'] = true;
            }
        }

        return response()->json($languages);
    }


    public function timezone()
    {
        $timezones = \Module::data('core::config.timezones');

        foreach ($timezones as $k => $timezone)
        {
            if ($timezone['id'] == config('app.timezone')) {

                $timezones[$k]['selected'] = true;
            }
        }

        return response()->json($timezones);
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
            $content->withWarning('提醒', $error);


        } else {

            // 写入ENV配置
            $this->setEnv([
                'APP_LOCALE'      => $request->input('lang'),
                'APP_TIMEZONE'    => $request->input('timezone'),
            ]);
            $content->withSuccess('提醒', '操作成功');

            return redirect(route('core.config.local'));

        }

    }

    public function safeStore(Content $content, Request $request)
    {
        $data = $request->post();

        $validate = \Illuminate\Support\Facades\Validator::make($data,
            [
                'env' => 'required',
                'debug' => 'required',
            ]);

        if ($validate->fails()) {

            $error = $validate->errors()->first();
            $content->withWarning('提醒', $error);


        } else {

            // 写入ENV配置
            $this->setEnv([
                'APP_ENV'      => $request->input('env'),
                'APP_DEBUG'    => $request->input('debug') == 'on' ? 'true' : 'false',
            ]);
            $content->withSuccess('提醒', '操作成功');

            return redirect(route('core.config.index'));

        }

    }
}
