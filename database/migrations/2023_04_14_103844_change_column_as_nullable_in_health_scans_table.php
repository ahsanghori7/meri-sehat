<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnAsNullableInHealthScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_scans', function (Blueprint $table) {
            $table->string('heart_rate')->nullable()->change();
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
            $table->string('heart_rate')->nullable(false)->change();
        });
    }
}
