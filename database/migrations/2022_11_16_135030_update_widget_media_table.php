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
        Schema::table('widget_media', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('is_default');
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
        Schema::table('widget_media', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_mobile_show');
        });
    }
};
