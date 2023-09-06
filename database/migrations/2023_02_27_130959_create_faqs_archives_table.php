<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('faqs_archives'))
            Schema::connection('mysql3')->create('faqs_archives', function (Blueprint $table){
                $table->id();
                $table->integer('sequence')->default(1);
                $table->bigInteger('category_id')->unsigned()->nullable();
                $table->text('question');
                $table->text('answer');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->bigInteger('faqs_id')->unsigned()->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('faqs_archives', function (Blueprint $table) {
            Schema::dropIfExists('faqs_archives');
        });
    }
}
