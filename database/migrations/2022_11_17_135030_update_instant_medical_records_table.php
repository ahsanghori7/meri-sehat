<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstantMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('instant_medical_record_files', function (Blueprint $table) {
            $table->renameColumn('medical_record_id', 'instant_medical_record_id');
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
        Schema::table('instant_medical_record_files', function (Blueprint $table) {
            $table->dropColumn('is_web_show');
        });
    }
}
