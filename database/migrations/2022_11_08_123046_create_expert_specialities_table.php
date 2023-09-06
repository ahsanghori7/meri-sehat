<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertSpecialitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
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
        Schema::dropIfExists('expert_specialities');
    }
}
