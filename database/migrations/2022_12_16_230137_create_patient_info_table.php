<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_info', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
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
        Schema::dropIfExists('patient_info');
    }
}
