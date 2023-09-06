<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('deductions')){
            Schema::create('deductions', function (Blueprint $table) {
                $table->id();
                $table->integer('doctor_earning_id')->default(0);
                $table->decimal('total_receivable', 9, 3)->default(0);
                $table->decimal('amount_paid', 9, 3)->default(0);
                $table->string('progress')->default('cancel');
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
        if (Schema::connection('mysql')->hasTable('deductions')){
            Schema::dropIfExists('deductions');
        }
    }
}
