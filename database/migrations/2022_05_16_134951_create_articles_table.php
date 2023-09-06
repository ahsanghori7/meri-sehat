<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('written_by');
            $table->foreign('written_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('lang_id')->nullable()->unsigned()->index();
            $table->string('type', 50);
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('descripton');
            $table->string('keywords', 100);
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('articles');
    }
}
