<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSharedMedicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shared_medical_reports', function (Blueprint $table) {
        $table->unsignedBigInteger('medical_record_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shared_medical_reports', function (Blueprint $table) {
            $table->dropColumn('medical_record_id');
        });
    }
}
