<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescribedElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescribed_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            $table->unsignedBigInteger('prescription_element_id');
            $table->foreign('prescription_element_id')->references('id')->on('prescription_elements')->onDelete('cascade');
            $table->integer('number_of_days');
            $table->double('dosage');
            $table->integer('per_day');
            $table->boolean('is_after_meal')->default(true);
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
        Schema::dropIfExists('prescribed_elements');
    }
}
