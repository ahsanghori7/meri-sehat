<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorPayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('doctor_payables')){
            Schema::create('doctor_payables', function (Blueprint $table) {
                $table->id();
                $table->integer('doctor_earning_id')->default(0);
                $table->decimal('total_receivable', 9, 3)->default(0);
                $table->decimal('amount_paid', 9, 3)->default(0);
                $table->string('progress')->default('completed');
                $table->string('status')->default('unpaid');
                $table->softDeletes();
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
        if (Schema::connection('mysql')->hasTable('doctor_payables')){
            Schema::dropIfExists('doctor_payables');
        }
    }
}
