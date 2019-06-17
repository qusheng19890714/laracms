<?php

namespace Modules\Topic\Http\Controllers\Admin;

use Encore\Admin\Widgets\Table;
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
            ->header(trans('topic::topic.edit.header'))
            ->breadcrumb(['url'=>'topic/topics', 'text'=>trans('topic::topic.list.header')], ['text'=>trans('topic::topic.edit.header')])
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
            ->header(trans('topic::topic.create.header'))
            ->breadcrumb(['url'=>'topic/topics', 'text'=>trans('topic::topic.list.header')], ['text'=>trans('topic::topic.create.header')])
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

        //禁用导出按钮
        $grid->disableExport();

        $grid->column('id', 'ID')->sortable();
        $grid->column('title', trans('topic::topic.title.label'))->modal('最新评论', function($model){

            $comments = $model->replies()->take(10)->orderBy('created_at', 'DESC')->get()->map(function($comment){

                return $comment->only(['id', 'content', 'created_at']);
            });

            return new Table(['ID', '内容', '发布时间'], $comments->toArray());

        });
        $grid->column('user.name', trans('topic::topic.author.label'));
        $grid->column('category.name', trans('topic::topic.category.label'));
        //$grid->column('reply_count', trans('topic::topic.reply_count.label'));
        //$grid->column('view_count', trans('topic::topic.view_count.label'));
        $grid->column('status', trans('topic::topic.status.label'))->display(function ($status){

            if ($status == 1) {

                return "<span class='label label-success'>".trans('core::master.status.normal')."</span>";

            }else {

                return "<span class='label label-danger'>".trans('core::master.status.delete')."</span>";
            }

        });

        $grid->column('order', trans('topic::topic.order.label'))->sortable();

        $grid->column('created_at', trans('topic::topic.created_at.label'))->sortable();

        //筛选
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->like('title', trans('topic::topic.title.label'));
            $filter->equal('category_id', trans('topic::topic.category.label'))->select(Category::selectOptions());
            $filter->between('created_at', trans('topic::topic.created_at.label'))->datetime();
        });






        $grid->actions(function ($action) {

            $action->disableView();

            $id = $action->getKey();

            $action->prepend('<a href="'.route('topic.reply', $id).'"><i class="fa fa-comment"></i></a>');
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

        $form->tools(function($tool) {

            $tool->disableView();

        });

        return $form;
    }


    public function destroy($id)
    {
        Topic::where('id', $id)->update(['status'=>0]);
    }
}
