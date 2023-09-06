<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('doctor_earnings')){

            Schema::create('doctor_earnings', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->default(0);
                $table->integer('doctor_id')->default(0);
                $table->decimal('total_payable', 9, 3)->default(0);
                $table->decimal('remaining_payable', 9, 3)->default(0);
                $table->decimal('income', 9, 3)->default(0);
                $table->decimal('deduction', 9, 3)->default(0);
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
        if (Schema::connection('mysql')->hasTable('doctor_earnings')){
            Schema::dropIfExists('doctor_earnings');
        }
    }
}
