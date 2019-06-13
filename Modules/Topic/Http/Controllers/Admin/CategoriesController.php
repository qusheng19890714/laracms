<?php

namespace Modules\Topic\Http\Controllers\Admin;

use Modules\Topic\Entities\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CategoriesController extends Controller
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
            ->header(trans('topic::category.title'))
            ->breadcrumb(['text'=>trans('topic::category.title')])
            ->body(Category::tree());
    }

    /**
     * Show interface.(废弃)
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
            ->header(trans('topic::category.edit'))
            ->breadcrumb(['text'=> trans('topic::category.title'), 'url'=>'topic/categories'], ['text'=>trans('topic::category.edit')])
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
            ->header(trans('topic::category.create'))
            ->breadcrumb(['text'=> trans('topic::category.title'), 'url'=>'topic/categories'], ['text'=>trans('topic::category.create')])
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->id('Id');
        $grid->pid('Pid');
        $grid->name('Name');
        $grid->description('Description');
        $grid->sort('Sort');
        $grid->status('Status');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.(废弃)
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Category::findOrFail($id));

        $show->id('Id');
        $show->pid('Pid');
        $show->name('Name');
        $show->description('Description');
        $show->sort('Sort');
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
        $form = new Form(new Category);

        $form->tools(function(Form\Tools $tools) {

            $tools->disableView();

        });

        $form->select('pid', trans('topic::category.parent.label'))->options(Category::selectOptions());
        $form->text('name',  trans('topic::category.title.label'))->rules('required|string');
        $form->textarea('description', trans('topic::category.description.label'));
        $form->number('sort', trans('topic::category.sort.label'));
        $form->switch('status', trans('topic::category.status.label'));

        return $form;
    }
}
