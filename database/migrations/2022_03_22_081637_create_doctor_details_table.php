<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('prefix', 20)->nullable();
            $table->integer('experience_year');
            $table->string('pmc_no',12);
            $table->string('badge', 20)->nullable();
            $table->text('about')->nullable();
            $table->double('waiting_time')->nullable();
            $table->text('collaborations')->nullable();
            $table->time('consultation_duration')->formate('H:m')->nullable();
            $table->boolean('is_physical_consultancy')->default(false);
            $table->boolean('is_video_consultancy')->default(false);
            $table->boolean('is_voice_consultancy')->default(false);
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
        Schema::dropIfExists('doctor_details');
    }
}
