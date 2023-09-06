<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionColumnsInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('ms_commission')->nullable()->after('status');
            $table->boolean('ms_commission_is_percentage')->nullable()->after('ms_commission');
            $table->integer('doctor_total')->nullable()->after('ms_commission_is_percentage');
            $table->integer('ms_total')->nullable()->after('doctor_total');
            $table->integer('grand_total')->nullable()->after('ms_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(array('ms_commission','ms_commission_is_percentage','doctor_total','ms_total','grand_total'));
        });
    }
}
