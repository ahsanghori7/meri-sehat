<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsLoginCredentialsSentFieldInDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctor_details', function (Blueprint $table) {
            $table->boolean('is_login_credentials_sent')->default(false);
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
            $table->dropColumn('is_login_credentials_sent');
        });
    }
}
