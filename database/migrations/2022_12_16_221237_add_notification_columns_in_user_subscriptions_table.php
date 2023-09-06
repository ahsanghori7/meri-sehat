<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationColumnsInUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->boolean('is_three_day_expire_message')->default(0);
            $table->boolean('is_three_day_expire_email')->default(0);
            $table->boolean('is_three_day_expire_push_notification')->default(0);
            $table->boolean('is_day_expire_message')->default(0);
            $table->boolean('is_day_expire_push_notification')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn('is_three_day_expire_message');
            $table->dropColumn('is_three_day_expire_email');
            $table->dropColumn('is_three_day_expire_push_notification');
            $table->dropColumn('is_day_expire_message');
            $table->dropColumn('is_day_expire_push_notification');
        });
    }
}
