<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddPriorityAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('priority', ['standard', 'priority'])->default('standard')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
}
