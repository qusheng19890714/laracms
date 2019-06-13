<?php

namespace Modules\Topic\Observers;

use Encore\Admin\Facades\Admin;
use Modules\Topic\Entities\Topic;
use Modules\Topic\Jobs\TranslateSlug;
use Overtrue\Pinyin\Pinyin;


class TopicObserver
{

    public function creating(Topic $topic)
    {
        $topic->user_id = Admin::user()->id;
    }

    public function saving(Topic $topic)
    {
        //避免xss漏洞
        $topic->body = clean($topic->body, 'user_topic_body');



        //$topic->save();
    }


    public function saved(Topic $topic)
    {
        if (!$topic->slug) {

            //推送到队列
            dispatch(new TranslateSlug($topic));
        }
    }

}