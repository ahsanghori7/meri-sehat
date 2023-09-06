<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubscriptionsAddColumnsInSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('duration_yearly', 50)->nullable()->after('duration');
            $table->string('duration_text_yearly', 100)->nullable()->after('duration_text');
            $table->double('price_yearly')->nullable()->after('price');
            $table->double('discounted_price_yearly')->nullable()->after('discounted_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('duration_yearly');
            $table->dropColumn('duration_text_yearly');
            $table->dropColumn('price_yearly');
            $table->dropColumn('discounted_price_yearly');
        });
    }
}
