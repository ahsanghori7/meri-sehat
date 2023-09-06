<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDoctorDetailsTable extends Migration
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
            $table->boolean('profile_status')->default(true)->after('last_call_at')->change();
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
            $table->boolean('profile_status')->default(0)->after('is_available');
        });
    }
}
