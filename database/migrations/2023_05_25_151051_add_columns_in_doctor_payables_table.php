<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_payables', function (Blueprint $table) {
            $table->string('bank_transaction_id')->nullable();
            $table->date('transaction_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_payables', function (Blueprint $table) {
            $table->dropColumn('bank_transaction_id');
            $table->dropColumn('transaction_date');
        });
    }
};