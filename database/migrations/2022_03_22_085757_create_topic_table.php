<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('translation_of')->nullable()->unsigned()->index();
            $table->bigInteger('lang_id')->unsigned()->index();
            $table->bigInteger('parent_id')->unsigned()->index();
            $table->bigInteger('sequence')->default(0);
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('color_code');
            $table->text('outer_image');
            $table->text('outer_text');
            $table->text('banner_text');
            $table->text('banner_image');
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
        Schema::dropIfExists('topics');
    }
}
