<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedMedicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_medical_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');
            $table->string('type', 50);
            $table->unsignedBigInteger('uploaded_by');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('shared_medical_reports');
    }
}
