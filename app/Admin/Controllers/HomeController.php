<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use Modules\User\Entities\User;
use Modules\Topic\Entities\Topic;
use Modules\Topic\Entities\Reply;
use Nwidart\Modules\Facades\Module;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;


class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header(trans('core::dashboard.header'))
            ->description(date('Y-m-d'))
            ->row(function (Row $row) {

                $userModule = Module::findOrFail('User');

                if ($userModule->installed == 1) { //安装用户模块

                    //今日新增用户
                    $row->column(4, function (Column $column)  {

                        //今日注册人数
                        $new_user_count = User::where('created_at', '>', Carbon::today())->count();

                        $column->append(new InfoBox(trans('core::dashboard.register.count'), 'user/users', 'aqua', 'admin/users', $new_user_count));

                    });
                }

                $topicModule = Module::findOrFail('Topic');

                if ($topicModule->installed == 1) { //安装新闻模块

                    $row->column(4, function (Column $column) {

                        //今日话题数量
                        $new_topic_count = Topic::where('created_at', '>', Carbon::today())->count();

                        $column->append(new InfoBox(trans('core::dashboard.topic.count'), 'copy', 'green', 'admin/topic/topics', $new_topic_count));
                    });

                    $row->column(4, function (Column $column) {

                        //今日评论数量
                        $new_reply_count = Reply::where('created_at', '>', Carbon::today())->count();

                        $column->append(new InfoBox(trans('core::dashboard.reply.count'), 'comment', 'yellow', '', $new_reply_count));
                    });
                }

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });

            });
    }
}
