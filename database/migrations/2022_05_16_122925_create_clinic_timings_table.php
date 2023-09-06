<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinic_timings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_clinic_id');
            $table->foreign('doctor_clinic_id')->references('id')->on('doctor_clinics')->onDelete('cascade');
            $table->string('day', 50);
            $table->time('start_time')->formate('H:i');
            $table->time('end_time')->formate('H:i');
            $table->boolean('is_physical')->default(true);
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
        Schema::dropIfExists('clinic_timings');
    }
}
