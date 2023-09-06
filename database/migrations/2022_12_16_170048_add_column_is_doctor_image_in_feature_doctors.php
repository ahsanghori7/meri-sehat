<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsDoctorImageInFeatureDoctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature_doctors', function (Blueprint $table) {
            $table->string('doctor_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature_doctors', function (Blueprint $table) {
            $table->dropColumn('doctor_image');
        });
    }
}
