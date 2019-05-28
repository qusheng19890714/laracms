<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Traits\ModuleConfig;
use Modules\User\Entities\AuthorConfig;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AuthorConfigController extends Controller
{
    use HasResourceActions, ModuleConfig;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('user::user.authorization.index'))
            ->breadcrumb(['text'=>trans('user::user.authorization.config')])
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AuthorConfig);

        //禁用新增, 导出, 筛选按钮
        //$grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableColumnSelector();
        //$grid->disableActions();
        $grid->actions(function($action) {

            $action->disableView();
        });

        $grid->column('name', trans('user::user.authorization.name.label'));

        $states = [

            'on'  => ['value'=>1, 'text'=>trans('core::master.open'), 'color'=>'primary'],
            'off' => ['value'=>0, 'text'=>trans('core::master.close'), 'color'=>'default']
        ];

        $grid->column('res_name', trans('user::user.authorization.res_name.label'));
        $grid->column('status', trans('core::master.status.label'))->status()->switch($states);
        $grid->column('created_at', trans('core::master.created_at.label'));


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AuthorConfig::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->data('Data');
        $show->status('Status');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AuthorConfig);

        $form->text('name', trans('user::user.authorization.name.label'))->rules('required');
        $form->text('res_name', trans('user::user.authorization.res_name.label'))->rules('required');

        $form->embeds('data', trans('user::user.authorization.config'), function($form) {

            $form->text('app_id',trans('user::user.app_id.label'))->rules('required|string');
            $form->text('app_secret', trans('user::user.app_secret.label'))->rules('required|string');
            $form->url('redirect', trans('user::user.redirect.label'))->rules('required');

        });

        $form->switch('status', trans('core::master.status.label'));

        $form->submitted(function() {

            // 写入ENV配置
            $this->setEnv([
                'WEIXIN_APP_ID'      =>  request()->data['app_id'],
                'WEIXIN_APP_SECRET'   => request()->data['app_secret'],
                'WEIXIN_REDIRECT_URI' => request()->data['redirect'],
            ]);

        });

        return $form;
    }

    /**
     * 配置保存
     * @param Content $content
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function config_store(Content $content, Request $request)
    {
        $data = $request->post();

        $validate = \Illuminate\Support\Facades\Validator::make($data,
            [
                'weixin_app_id' => 'required|string',
                'weixin_app_secret' => 'required|string',
                'weixin_redirect' =>'url',
            ]);

        if ($validate->fails()) {

            $error = $validate->errors()->first();
            $content->withWarning(trans('core::master.warning'), $error);


        } else {

            // 写入ENV配置
            $this->setEnv([
                'WEIXIN_APP_ID'      =>  $request->weixin_app_id,
                'WEIXIN_APP_SECRET'   => $request->weixin_app_secret,
                'WEIXIN_TOKEN'        => $request->weixin_token,
                'WEIXIN_REDIRECT_URI' => $request->weixin_redirect,
            ]);
            $content->withSuccess(trans('core::master.success'), trans('core::master.saved'));

            return redirect(route('authorconfig.index'));

        }
    }

}
