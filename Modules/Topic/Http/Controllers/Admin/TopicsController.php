<?php

namespace Modules\Topic\Http\Controllers\Admin;

use Modules\Topic\Entities\Category;
use Modules\Topic\Entities\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class TopicsController extends Controller
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
            ->header(trans('topic::topic.list.header'))
            ->description(trans('topic::topic.list.description'))
            ->breadcrumb(['text'=>trans('topic::topic.list.header')])
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
        $grid = new Grid(new Topic);

        $grid->column('id', 'ID')->sortable();
        $grid->column('title', trans('topic::topic.title.label'));
        $grid->column('user.name', trans('topic::topic.author.label'));
        $grid->column('category.name', trans('topic::topic.category.label'));
        //$grid->column('reply_count', trans('topic::topic.reply_count.label'));
        //$grid->column('view_count', trans('topic::topic.view_count.label'));
        $grid->column('order', trans('topic::topic.order.label'));
        $grid->column('status', trans('topic::topic.status.label'));
        $grid->column('created_at', trans('topic::topic.created_at.label'))->sortable();

        $grid->actions(function ($action) {

            $action->disableView();
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
        $show = new Show(Topic::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->category_id('Category id');
        $show->title('Title');
        $show->slug('Slug');
        $show->excerpt('Excerpt');
        $show->body('Body');
        $show->reply_count('Reply count');
        $show->view_count('View count');
        $show->last_reply_user_id('Last reply user id');
        $show->order('Order');
        $show->tags('Tags');
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
        $form = new Form(new Topic);

        $form->select('category_id', trans('topic::topic.category.label'))->options(Category::selectOptions());
        $form->text('title', trans('topic::topic.title.label'));
        $form->text('slug', trans('topic::topic.slug.label'))->help(trans('topic::topic.slug.help'));
        $form->textarea('excerpt', trans('topic::topic.excerpt.label'));
        $form->simditor('body', trans('topic::topic.body.label'));
        $form->number('order', trans('topic::topic.order.label'))->help(trans('topic::topic.order.help'));
        $form->tags('tags', trans('topic::topic.tags.label'));
        $form->switch('status', trans('topic::topic.status.label'))->default(1);

        return $form;
    }
}
