<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookedViaSubscriptionDataTypeInAppointAndPreAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('booked_via_subscription')->default(null)->nullable()->after('id')->change();
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->string('booked_via_subscription')->default(null)->nullable()->after('id')->change();
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
            $table->boolean('booked_via_subscription')->default(0)->after('id')->change();
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->boolean('booked_via_subscription')->default(0)->after('id')->change();
        });
    }
}
