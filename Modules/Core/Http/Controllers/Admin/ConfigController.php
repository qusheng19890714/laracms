<?php

namespace Modules\Core\Http\Controllers\Admin;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Tab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Modules\Core\Entitties\Config;
use Modules\Core\Traits\ModuleConfig;
use Auth;
use Modules\Core\Traits\ImageUploadHandler;
//use Nwidart\Modules\Module;

class ConfigController extends Controller
{
    use HasResourceActions, ModuleConfig;

    public function index(Content $content)
    {
        $content->header(trans('core::config.index'));
        $content->breadcrumb(['text'=>trans('core::config.index')]);

        //构建tab
        $tab = new Tab();

        //网站信息
        $siteConfig = Config::get('site');

        $site = new \Encore\Admin\Widgets\Form($siteConfig);

        $site->action(route('core.config.site.store'));

        $site->text('name', trans('core::config.site.name.label'))->required()->rules('string');
        $site->url('url', trans('core::config.site.url.label'))->required()->rules('url|string');
        $site->text('copyright', trans('core::config.site.copyright.label'))->rules('string');
        $site->image('logo', trans('core::config.site.logo.label'))->help(trans('core::config.site.logo.help'))->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208');
        $site->image('favicon', trans('core::config.site.fav.label'))->help(trans('core::config.site.fav.help'))->rules('mimes:png|dimensions:min_width=16,min_height=16');

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

        //site
        $tab->add(trans('core::config.site.tab'), $site);
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


    public function siteStore(Content $content, Request $request, ImageUploadHandler $imageUploadHandler)
    {

        $data = $request->only('name', 'url', 'copyright');

        if ($request->logo) {

            $result = $imageUploadHandler->save($request->logo, 'logo', Admin::user()->id, '1024');

            $data['logo'] = $result['path'];
        }

        if ($request->favicon) {

            $result = $imageUploadHandler->save($request->favicon, 'favicon', Admin::user()->id, '100');

            $data['favicon'] = $result['path'];
        }


        $this->config('site', $data);

        $content->withSuccess(trans('core::master.success'), trans('core::master.saved'));

        return redirect(route('core.config.index'));
    }


    public function upload(Request $request, ImageUploadHandler $imageUploadHandler)
    {
        $data = [

            'success'=>false,
            'msg' => trans('core::master.upload.failed'),
            'file_path' => '',
        ];

        if ($request->upload_file) {

            $result = $imageUploadHandler->save($request->upload_file, 'simditor', Admin::user()->id, '1024');

            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = trans('core::master.upload.success');
                $data['success']   = true;
                $data['data'][]    = $result['path'];
            }
        }

        return $data;

    }
}
