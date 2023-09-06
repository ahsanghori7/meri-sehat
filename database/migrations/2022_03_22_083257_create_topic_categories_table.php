<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id');
            $table->integer('layout_id');
            $table->string('title');
            $table->foreignId('content_id');
            $table->integer('status');
            $table->integer('sequence');
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
        Schema::dropIfExists('topic_categories');
    }
}
