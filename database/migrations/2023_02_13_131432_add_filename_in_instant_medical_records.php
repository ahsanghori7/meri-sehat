<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilenameInInstantMedicalRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->table('instant_medical_records', function (Blueprint $table) {
            $table->string('filename')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->table('instant_medical_records', function (Blueprint $table) {
            $table->dropColumn('filename');
        });
    }
}
