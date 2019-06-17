<?php

namespace Modules\User\Http\Controllers\Admin;

use Modules\User\Export\UsersExcelExport;
use Modules\User\Entities\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Module;

class IndexController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('user::user.index'))
            ->breadcrumb(['text'=> trans('user::user.index')])
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
            ->header(trans('user::user.edit'))
            ->breadcrumb(['text'=>trans('user::user.title'),'url'=>'admin/user/users'], ['text'=>trans('user::user.edit')])
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
            ->header(trans('user::user.create'))
            ->breadcrumb(['text'=>trans('user::user.title'),'url'=>'admin/user/users'], ['text'=>trans('user::user.create')])
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->id('ID')->sortable();
        $grid->column('name', trans('user::user.name.label'))->display(function($title){

            return '<div class="text-sm">'.$title.'</div>';

        });
        $grid->column('avatar', trans('user::user.avatar.label'))->image('',25,25);

        $grid->column('authorization.type', trans('user::user.login.type.label'))->display(function() {

            $login_type = Module::data('user::login.type');

            return '<div class="text-sm">'.$login_type[$this->authorization['type']].'</div>';

        });

        $grid->column('authorization.identifier', trans('user::user.identifier.label'))->display(function() {

            return '<div class="text-sm">'.$this->authorization['identifier'].'</div>';

        });

        $grid->column('authorization.status', trans('user::user.status.label'))->display(function() {

            $status = Module::data('user::status');

            return '<div class="text-sm">'.$status[$this->authorization['status']].'</div>';

        });

        $grid->column('created_at', trans('user::user.created_at.label'))->display(function($created_at) {

            return '<div class="text-sm">'.$created_at.'</div>';
        });

        //查询
        $grid->filter(function($filter) {

            $filter->like('authorization.identifier', trans('user::user.identifier.label'));
            $filter->between('created_at', trans('user::user.created_at.label'))->datetime();

        });

        //禁用view
        $grid->actions(function($action) {

            $action->disableView();

        });

        //excel导出数据
        $grid->exporter(new UsersExcelExport());

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
        $show = new Show(User::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->email('Email');
        $show->email_verified_at('Email verified at');
        $show->password('Password');
        $show->remember_token('Remember token');
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
        $form = new Form(new User);

        //用户名
        $form->text('name', trans('user::user.name.label'))->rules('required|string|max:255');

        //头像
        $form->image('avatar', trans('user::user.avatar.label'))->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208')->help('头像必须是 jpeg, bmp, png, gif 格式的图片');

        //展示登录类型
        $form->select('authorization.type', trans('user::user.login.type.label'))->options(Module::data('user::login.type'))->rules('required');

        //登录账号
        $form->text('authorization.identifier', trans('user::user.identifier.label'))->rules(function($form) {


            if (request()->input('authorization.type') == 'phone') {

                if (!$id = $form->model()->id) {

                    return 'required|string|max:255|unique:authorizations,identifier';
                }

                return 'required|string|max:255|unique:authorizations,identifier,'. $form->model()->id;

            }

            if (request()->input('authorization.type') == 'email') {

                if (!$id = $form->model()->id) {

                    return 'required|email|string|max:255|unique:authorizations,identifier';
                }

                return 'required|email|string|max:255|unique:authorizations,identifier,'. $form->model()->id;
            }

            return 'required|string|max:255';

        })->required();

        //密码
        $form->password('authorization.password', trans('user::user.password.label'))->rules('required|confirmed');
        $form->password('authorization.password_confirmation', trans('user::user.password.confirm.label'))->rules('required')->default(function($form){

            return $form->model()->authorization['password'];
        });

        $form->ignore(['authorization.password_confirmation']);

        //状态
        $form->radio('authorization.status', trans('user::user.status.label'))->options([1=>trans('user::user.status.normal'), 0=>trans('user::user.status.delete')])->default(1);

        $form->saving(function(Form $form) {

            $authorization = $form->authorization;

            if ($authorization['password'] && $form->model()->authorization['password'] != $authorization['password']) {

                $authorization['password'] = bcrypt($authorization['password']);

                $form->authorization = $authorization;
            }
        });


        //底部
        $form->footer(function($footer) {

            //去掉"查看"checkbox
            $footer->disableViewCheck();
            //去掉"继续编辑"checkbox
            $footer->disableEditingCheck();
            //去掉"继续创建"checkbox
            $footer->disableCreatingCheck();

        });

        return $form;
    }

}
