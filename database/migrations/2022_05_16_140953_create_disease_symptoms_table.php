<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_symptoms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('symptom_id');
            $table->foreign('symptom_id')->references('id')->on('symptoms')->onDelete('cascade');
            $table->unsignedBigInteger('disease_id');
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
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
        Schema::dropIfExists('disease_symptoms');
    }
}
