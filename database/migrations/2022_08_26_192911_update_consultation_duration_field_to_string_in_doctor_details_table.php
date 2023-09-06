<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateConsultationDurationFieldToStringInDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->string('consultation_duration', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->time('consultation_duration')->nullable()->change();
        });
    }
}
