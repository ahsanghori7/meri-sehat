<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorEarningDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('doctor_earning_detail')){

            Schema::create('doctor_earning_detail', function (Blueprint $table) {
                $table->id();
                $table->integer('doctor_earning_id')->default(0);
                $table->integer('appointment_id')->default(0);
                $table->string('status')->default('unpaid');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql')->hasTable('doctor_earning_detail')){
            Schema::dropIfExists('doctor_earning_detail');
        }
    }
}
