<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('clinic_id');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->foreign('family_member_id')->references('id')->on('family_members')->onDelete('cascade');
            $table->double('consultation_fee');
            $table->string('reason')->nullable();
            $table->string('type', 50);
            $table->date('date')->formate('Y-m-d');
            $table->time('time')->formate('H:m');
            $table->string('progress', 50);
            $table->unsignedBigInteger('action_by');
            $table->foreign('action_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('appointments');
    }
}
