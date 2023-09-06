<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentPayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('appointment_payables')){
            Schema::create('appointment_payables', function (Blueprint $table) {
                $table->id();
                $table->integer('doctor_payable_id')->default(0);
                $table->integer('appointment_id')->default(0);
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
        if (Schema::connection('mysql')->hasTable('appointment_payables')){
            Schema::dropIfExists('appointment_payables');
        }
    }
}
