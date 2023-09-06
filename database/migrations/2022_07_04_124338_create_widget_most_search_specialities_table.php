<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetMostSearchSpecialitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_most_search_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->unsigned()->index();
            $table->unsignedBigInteger('speciality_id');
            $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('cascade');
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
        Schema::dropIfExists('widget_most_search_specialities');
    }
}
