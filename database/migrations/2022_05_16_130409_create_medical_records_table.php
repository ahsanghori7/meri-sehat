<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->foreign('family_member_id')->references('id')->on('family_members')->onDelete('cascade');
            $table->unsignedBigInteger('prescription_id')->nullable();
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date')->formate('Y-m-d');
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
        Schema::dropIfExists('medical_records');
    }
}
