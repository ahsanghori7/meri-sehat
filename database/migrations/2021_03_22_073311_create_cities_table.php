<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('translation_of')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('lang_id');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
            $table->bigInteger('sequence')->default(0);
            $table->string('name', 100);
            $table->string('slug', 100);
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
        Schema::dropIfExists('cities');
    }
}
