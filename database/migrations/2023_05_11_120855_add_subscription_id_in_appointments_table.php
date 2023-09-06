<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionIdInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('subscription_id')->default(null)->nullable()->after('booked_via_subscription');
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->integer('subscription_id')->default(null)->nullable()->after('booked_via_subscription');
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
            $table->dropColumn('subscription_id');
        });

        Schema::table('pre_appointments', function (Blueprint $table) {
            $table->dropColumn('subscription_id');
        });
    }
}
