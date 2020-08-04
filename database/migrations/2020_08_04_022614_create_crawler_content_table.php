<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawlerContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_name')->default('')->comment('content name');
            $table->text('content_regx');
            $table->string('content_regx_type')->default('');
            $table->tinyInteger('save_flag')->default(0)->comment('0 passed 1 mysql 2 redis 3 local_log');
            $table->integer('visit_id')->default(0)->comment('to visit contents find url');
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
        Schema::dropIfExists('crawler_content');
    }
}
