<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddProfileStatusDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->boolean('profile_status')->default(0)->after('is_available');
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
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->dropColumn('profile_status');
        });
    }
}
