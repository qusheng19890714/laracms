<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Admin\Extensions\Install;
use App\Admin\Extensions\Uninstall;
use Illuminate\Http\Request;
use Modules\Core\Entities\Module;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;


class ModuleController extends Controller
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
        //dd(Module::hydrate(module()));

        return $content
            ->header(trans('core::module.index'))
            ->breadcrumb(['text'=>trans('core:module.index')])
            ->body($this->grid());
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Module);

        //禁用创建按钮
        $grid->disableCreateButton();

        //禁用查询过滤器
        $grid->disableFilter();

        //禁用行选择checkbox
        $grid->disableRowSelector();

        //禁用导出数据按钮
        $grid->disableExport();

        $grid->actions(function($actions){

            //核心模块不能安装和卸载
            if ($actions->row['code'] != 'Core') {

                $module = module($actions->row['code']);

                if ($module->installed == 0) {

                    //安装
                    $actions->append(new Install($actions->row['code']));

                }else {

                    //卸载
                    $actions->append(new Uninstall($actions->row['code']));
                }

            }

            //屏蔽常规操作(删除, 编辑, 查看)
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();

        });



        $grid->column('module_title', trans('core::module.name.label'))->display(function() {

            return $this->title . ' <span class="text-muted">('.$this->code.')</span>';

        });

        $grid->installed(trans('core::module.status.label'))->display(function($installed) {

            return $installed ? '<span class="label label-success">'.trans('core::module.install').'</span>' : '<span class="label label-danger">'.trans('core::module.uninstall').'</span>';
        });
        $grid->description(trans('core::module.description.label'));

        $grid->author(trans('core::module.author.label'))->display(function($author) {

            return '<span class="label label-primary">'.$author.'</span>';

        });

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Module);

        $form->footer(function ($footer) {

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });

        $form->text('title', trans('core::module.name.label'))->rules('required|min:2');
        $form->text('code', trans('core::module.code.label'))->rules('required|min:2');
        $form->textarea('description', trans('core::module.description.label'))->rules('required|min:2');
        $form->text('author', trans('core::module.author.label'))->rules('required|min:2');

        return $form;
    }

    //安装模块
    public function install($module)
    {

        $module = \Module::findOrFail($module);

        if ($module->installed == 1) {

            $data = [

                'status' => false,
                'message'=> trans('core::module.install.failed', [$module->title]),
            ];


        }else{

            $module->install();

            $data = [

                'status' => true,
                'message'=> trans('core::module.installed'),
            ];
        }

        return response()->json($data);
    }

    //卸载模块
    public function uninstall($module)
    {

        if ($module == 'Core') {

            $data = [

                'status' => false,
                'message'=> trans('core::module.core_operate_forbidden'),
            ];

        }else {

            $module = \Module::findOrFail($module);

            if ($module->installed == 0) {

                $data = [

                    'status' => false,
                    'message'=> trans('core::uninstall.failed', [$module->title]),
                ];


            }else{

                $module->uninstall();

                $data = [

                    'status' => true,
                    'message'=> trans('core::module.uninstalled'),
                ];
            }

        }

        return response()->json($data);
    }
}
