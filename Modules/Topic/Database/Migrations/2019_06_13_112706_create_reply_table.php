<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('topic_id')->unsigned()->default(0)->index()->comment('topic id');
            $table->bigInteger('user_id')->unsigned()->default(0)->index()->comment('用户id');
            $table->text('content')->comment('内容');
            $table->tinyInteger('status')->default(1)->comment('状态');

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
        Schema::dropIfExists('reply');
    }
}
