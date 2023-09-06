<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentDeductionssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('appointment_deductionss')){
            Schema::create('appointment_deductionss', function (Blueprint $table) {
                $table->id();
                $table->integer('deduction_id')->default(0);
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
        if (Schema::connection('mysql')->hasTable('appointment_deductionss')){
            Schema::dropIfExists('appointment_deductionss');
        }
    }
}
