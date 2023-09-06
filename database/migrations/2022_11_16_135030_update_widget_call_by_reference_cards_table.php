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
        //
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->boolean('is_mobile_show')->default(true)->after('status');
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
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->dropColumn('is_mobile_show');
        });
    }
};
