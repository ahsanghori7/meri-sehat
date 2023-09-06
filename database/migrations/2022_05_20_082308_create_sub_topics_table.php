<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_topics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('topic_id')->unsigned()->index();
            $table->bigInteger('translation_of')->nullable()->unsigned()->index();
            $table->bigInteger('lang_id')->unsigned()->index();
            $table->bigInteger('sequence')->default(0);
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->integer('status')->default(1);
            $table->bigInteger('created_by')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('sub_topics');
    }
}
