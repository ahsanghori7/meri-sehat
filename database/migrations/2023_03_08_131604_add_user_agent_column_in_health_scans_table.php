<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAgentColumnInHealthScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_scans', function (Blueprint $table) {
            $table->string('user_agent')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_scans', function (Blueprint $table) {
            $table->dropColumn('user_agent');
        });
    }
}
