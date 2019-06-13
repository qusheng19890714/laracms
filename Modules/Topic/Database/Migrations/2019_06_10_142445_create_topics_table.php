<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index()->default(0)->unsigned()->comment('用户id');
            $table->bigInteger('category_id')->default(0)->index()->unsigned()->comment('分类id');
            $table->string('title')->comment('标题');
            $table->string('slug')->nullable()->comment('SEO 友好的 URI');
            $table->text('excerpt')->nullable()->comment('摘要');
            $table->text('body')->comment('文章内容');
            $table->bigInteger('reply_count')->default(0)->unsigned()->index()->comment('回复数量');
            $table->bigInteger('view_count')->default(0)->unsigned()->index()->comment('查看数量');
            $table->bigInteger('last_reply_user_id')->default(0)->unsigned()->index()->comment('最后回复人');
            $table->bigInteger('order')->default(0)->comment('排序');
            $table->string('tags')->comment('标签');
            $table->tinyInteger('status')->default(1)->comment('文章状态');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
