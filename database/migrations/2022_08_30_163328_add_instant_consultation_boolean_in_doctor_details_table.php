<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstantConsultationBooleanInDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->boolean('is_instant_consultation')->default(0)->after('waiting_time');
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
            $table->dropColumn('is_instant_consultation');
        });
    }
}
