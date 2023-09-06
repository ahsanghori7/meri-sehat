<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMeetingDateAndMeetingTimeInDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->string('meeting_date')->nullable();
            $table->string('meeting_time')->nullable();
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
            $table->dropColumn('meeting_date');
            $table->dropColumn('meeting_time');
        });
    }
}
