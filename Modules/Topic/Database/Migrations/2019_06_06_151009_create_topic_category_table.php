<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_category', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('pid')->default(0)->comment('上级id');
            $table->string('name')->comment('分类名称');
            $table->string('description')->comment('描述');
            $table->integer('sort')->comment('排序');
            $table->tinyInteger('status')->comment('状态');

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
        Schema::dropIfExists('topic_category');
    }
}
