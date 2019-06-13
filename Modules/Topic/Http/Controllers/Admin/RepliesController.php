<?php

namespace Modules\Topic\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
use Modules\Topic\Entities\Reply;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Modules\Topic\Entities\Topic;

class RepliesController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */

    public function replies(Topic $topic,Content $content)
    {

        return $content
            ->header(trans('topic::reply.list.header'))
            ->description($topic->title)
            ->breadcrumb(['url'=>'topic/topics', 'text'=>trans('topic::topic.list.header')],
                         ['url'=>'topic/topics/'.$topic->id.'/edit', 'text'=>$topic->title],
                         ['text'=>trans('topic::reply.list.header')])
            ->body($this->grid($topic->id));

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
    public function edit(Topic $topic, Reply $reply, Content $content)
    {
        return $content
            ->header(trans('topic::reply.edit.header'))
            ->breadcrumb(['url'=>'topic/topics', 'text'=>trans('topic::topic.list.header')],
                ['url'=>'topic/topics/'.$topic->id.'/edit', 'text'=>$topic->title],
                ['url'=>'topic/replies', 'text'=>trans('topic::reply.list.header')],
                ['text'=>trans('topic::reply.edit.header')])
            ->body($this->form()->edit($reply->id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Topic $topic,Content $content)
    {

        return $content
            ->header(trans('topic::reply.create.header'))
            ->breadcrumb(['url'=>'topic/topics', 'text'=>trans('topic::topic.list.header')],
                ['url'=>'topic/topics/'.$topic->id.'/edit', 'text'=>$topic->title],
                ['url'=>'topic/replies', 'text'=>trans('topic::reply.list.header')],
                ['text'=>trans('topic::reply.create.header')])
            ->body($this->form());
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($id)
    {
        $grid = new Grid(new Reply());

        $grid->model()->where('topic_id', $id);

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', trans('topic::reply.author.label'));
        $grid->column('content', trans('topic::reply.content.label'));

        $grid->column('status', trans('core::master.status.label'))->display(function ($status){

            if ($status == 1) {

                return "<span class='label label-success'>".trans('core::master.status.normal')."</span>";

            }else {

                return "<span class='label label-danger'>".trans('core::master.status.delete')."</span>";
            }

        });

        $grid->column('created_at', trans('core::master.created_at.label'));

        $grid->actions(function($action){

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
        $show = new Show(Reply::findOrFail($id));

        $show->id('Id');
        $show->topic_id('Topic id');
        $show->user_id('User id');
        $show->content('Content');
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
        $form = new Form(new Reply);

        $form->hidden('topic_id');
        $form->textarea('content', trans('topic::reply.content.label'));
        $form->switch('status', trans('core::master.status.label'));

        $form->tools(function($tool){

            $tool->disableView();
        });

        return $form;
    }

    public function destroy(Topic $topic,Reply $reply)
    {

        Reply::where('id',$reply->id)->update(['status'=>0]);
    }
}
