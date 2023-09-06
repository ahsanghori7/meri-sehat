<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionaireFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionaire_form_data', function (Blueprint $table) {
            $table->id();      
            $table->unsignedBigInteger('appointment_id')->unsigned()->index();
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->string('title')->nullable();
            $table->string('value')->nullable();
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
        Schema::dropIfExists('questionaire_form_data');
    }
}
