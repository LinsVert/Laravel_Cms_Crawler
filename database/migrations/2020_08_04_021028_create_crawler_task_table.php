<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawlerTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('task_name')->default('')->comment('task name');
            $table->integer('begin_visit_id')->default(0)->comment('start url');
            $table->string('crontab')->nullable()->comment('run times');
            $table->tinyInteger('flow_robot')->default(1)->comment('flow crawler protocol');
            $table->softDeletes();
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
        Schema::dropIfExists('crawler_task');
    }
}
