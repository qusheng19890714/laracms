<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // config
        Schema::create('config', function (Blueprint $table) {
            $table->string('module', 128)->comment('模块名称');
            $table->string('key', 128)->comment('键名');
            $table->text('value')->nullable()->comment('键值');
            $table->string('type', 10)->comment('类型');
            $table->primary(['key', 'module']);

            $table->engine = 'InnoDB';

            $table->comment = '设置';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config');
    }
}
