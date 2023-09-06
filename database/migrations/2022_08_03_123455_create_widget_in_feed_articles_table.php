<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetInFeedArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_in_feed_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('heading')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('widget_in_feed_articles');
    }
}
