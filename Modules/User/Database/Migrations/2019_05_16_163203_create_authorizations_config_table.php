<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('第三方平台名称');
            $table->string('res_name')->comment('简写');
            $table->tinyInteger('status')->default(0)->comment('是否开启');

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
        Schema::dropIfExists('authorizations_config');
    }
}
