<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreAppointmentIdInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('booked_via_subscription')->default(0)->after('id');
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->boolean('booked_via_subscription')->default(0)->after('id');
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
            $table->dropColumn('booked_via_subscription');
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->dropColumn('booked_via_subscription');
        });
    }
}
