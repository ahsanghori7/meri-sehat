<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_content', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->string('title');
            $table->string('keyword');
            $table->string('meta_description');
            $table->string('slug');
            $table->text('content');
            $table->string('language');
            $table->integer('status')->default(1);
            $table->integer('edit_by');
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
        Schema::dropIfExists('site_content');
    }
}
