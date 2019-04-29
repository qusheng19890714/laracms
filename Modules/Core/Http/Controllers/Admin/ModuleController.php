<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Admin\Extensions\Install;
use App\Admin\Extensions\Uninstall;
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
            ->header('Index')
            ->description('description')
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
        $grid = new Grid(new Module);


        $grid->actions(function($actions){

            //核心模块不能安装和卸载
            if ($actions->row['sort_name'] != 'Core') {

                $module = module($actions->row['sort_name']);

                if ($module->installed == 0) {

                    //安装
                    $actions->append(new Install($actions->row['sort_name']));

                }else {

                    //卸载
                    $actions->append(new Uninstall($actions->row['sort_name']));
                }

            }

            //屏蔽常规操作(删除, 编辑, 查看)
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();

        });



        $grid->column('module_title', '名称')->display(function() {

            return $this->title . ' <span class="text-muted">('.$this->sort_name.')</span>';

        });

        $grid->installed('是否安装')->display(function($installed) {

            return $installed ? '<span class="label label-success">是</span>' : '<span class="label label-danger">否</span>';
        });
        $grid->description('描述');

        $grid->author('开发者')->display(function($author) {

            return '<span class="label label-primary">'.$author.'</span>';

        });

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
        $show = new Show(Module::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Module);



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
