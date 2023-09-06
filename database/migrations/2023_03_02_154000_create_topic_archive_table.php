<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('topic_archives'))
            Schema::connection('mysql3')->create('topic_archives', function (Blueprint $table){
                $table->id();
                $table->bigInteger('translation_of')->nullable()->unsigned()->index();
                $table->bigInteger('lang_id')->unsigned()->index();
                $table->bigInteger('parent_id')->unsigned()->index();
                $table->bigInteger('sequence')->default(0);
                $table->text('meta_keyword')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->string('color_code')->nullable();
                $table->text('outer_image')->nullable();
                $table->text('outer_text')->nullable();
                $table->text('banner_text')->nullable();
                $table->integer('status')->default(1);
                $table->bigInteger('created_by')->nullable()->unsigned()->index();
                $table->timestamps();
                $table->unsignedBigInteger('visit_counts')->unsigned()->index();
                $table->string('outer_home_image',255)->nullable();
                $table->boolean('draft')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('topic_archives', function (Blueprint $table) {
            Schema::dropIfExists('topic_archives');
            });
    }
}
