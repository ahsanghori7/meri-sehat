<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentAppointmentColumnInDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->integer('current_appointment')->unsigned()->nullable()->after('is_voice_consultancy');
            $table->timestamp('last_call_at')->nullable()->after('current_appointment');
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
            $table->dropColumn(['current_appointment','last_call_at']);
        });
    }
}
