<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePrescribedElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescribed_elements', function (Blueprint $table) {
            $table->integer('number_of_days')->default(null)->nullable()->change();
            $table->string('dosage', 15)->default(null)->nullable()->change();
            $table->integer('per_day')->default(null)->nullable()->change();
            $table->boolean('is_after_meal')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescribed_elements', function (Blueprint $table) {
            $table->integer('number_of_days')->change();
            $table->double('dosage')->change();
            $table->integer('per_day')->change();
            $table->boolean('is_after_meal')->default(true)->change();
        });
    }
}
