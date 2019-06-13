<?php

namespace Modules\Topic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Topic\Entities\Reply;
use Modules\User\Entities\User;
use Modules\Topic\Entities\Topic;
use Faker\Generator;
class ReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        // 所有话题 ID 数组，如：[1,2,3,4]
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Generator::class);

        $replys = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index)
            use ($user_ids, $topic_ids, $faker)
            {
                // 从用户 ID 数组中随机取出一个并赋值
                $reply->user_id = $faker->randomElement($user_ids);

                // 话题 ID，同上
                $reply->topic_id = $faker->randomElement($topic_ids);
            });

        // 将数据集合转换为数组，并插入到数据库中
        Reply::insert($replys->toArray());
    }
}
