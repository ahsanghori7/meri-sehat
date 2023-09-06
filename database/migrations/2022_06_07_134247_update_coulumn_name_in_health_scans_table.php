<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoulumnNameInHealthScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_scans', function (Blueprint $table) {
            $table->string('blood_pressure', 50)->nullable()->change();
            $table->string('stress_level', 50)->nullable()->change();
            $table->string('respiratory_rate', 50)->nullable()->change();
            $table->string('spo2', 50)->nullable()->change();
            $table->renameColumn('heat_rate', 'heart_rate')->nullable()->change();
            $table->string('bmi', 50)->nullable()->change();
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
            //
        });
    }
}
