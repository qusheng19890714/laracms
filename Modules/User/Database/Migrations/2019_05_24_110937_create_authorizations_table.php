<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('authorizations');

        Schema::create('authorizations', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('type')->comment('登录类型:email,mobile,weixin....');
            $table->string('identifier')->nullable()->comment('邮箱, 手机号, 第三方标识');
            $table->string('credential')->nullable()->comment('站内的密码, 站外的不保存或保存token');
            $table->tinyInteger('verified')->default(1)->comment('是否验证');
            $table->ipAddress('ip')->nullable()->comment('ip地址');
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
        Schema::dropIfExists('authorizations');
    }
}
