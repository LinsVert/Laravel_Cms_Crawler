<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrawlerVisitHasContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_visit_has_content', function (Blueprint $table) {
            $table->integer('visit_id');
            $table->integer('content_id');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['visit_id', 'content_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawler_visit_has_content');
    }
}
