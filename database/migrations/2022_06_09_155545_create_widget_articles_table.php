<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->unsigned()->index();
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->string('type')->comment('featured, single-column, double-column');
            $table->string('sequence')->default(1);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('widget_articles');
    }
}
