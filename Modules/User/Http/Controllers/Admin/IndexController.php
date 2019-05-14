<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Admin\Extensions\Export\UserExcelExport;
use Modules\User\Entities\User;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        $grid = new Grid(new User);

        $grid->id('ID')->sortable();
        $grid->column('name', trans('user::user.name.label'));
        $grid->column('avatar', trans('user::user.avatar.label'))->image('',50,50);
        $grid->column('email', trans('user::user.email.label'));
        $grid->column('phone', trans('user::user.phone.label'));
        $grid->column('created_at', trans('user::user.created_at.label'));

        //查询
        $grid->filter(function($filter) {

            $filter->column(1/2, function($filter) {

                $filter->equal('phone', trans('user::user.phone.label'));
                $filter->between('created_at', trans('user::user.created_at.label'))->datetime();
            });

            $filter->column(1/2, function($filter) {

                $filter->like('name', trans('user::user.name.label'));
                $filter->like('email', trans('user::user.email.label'));

            });
        });

        //禁用view
        $grid->actions(function($action) {

            $action->disableView();

        });

        //excel导出数据
        $grid->exporter(new UserExcelExport());

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

        //邮箱
        $form->email('email', trans('user::user.email.label'))->rules(function($form) {

            if (!$id = $form->model()->id) {

                return 'required|email|string|max:255|unique:users,email';
            }

            return 'required|email|string|max:255|unique:users,email,'. $form->model()->id;

        });

        //手机号
        $form->mobile('phone', trans('user::user.phone.label'))->rules(function($form) {

            if (!$id = $form->model()->id) {

                return 'required|string|max:255|unique:users,phone';
            }

            return 'required|string|max:255|unique:users,phone,'.$form->model()->id;

        });

        //头像
        $form->image('avatar', trans('user::user.avatar.label'))->rules('mimes:jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208')->help('头像必须是 jpeg, bmp, png, gif 格式的图片');

        //密码
        $form->password('password', trans('user::user.password.label'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('user::user.password.confirm.label'))->rules('required')
            ->default(function ($form) {

                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        //注册时间
        $form->display('created_at', trans('user::user.created_at.label'));


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
