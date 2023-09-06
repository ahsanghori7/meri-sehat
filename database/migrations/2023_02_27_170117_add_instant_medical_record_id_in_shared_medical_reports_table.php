<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstantMedicalRecordIdInSharedMedicalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->table('shared_medical_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('instant_medical_record_id')->nullable();
            $table->foreign('instant_medical_record_id')->references('id')->on('instant_medical_records')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->table('shared_medical_reports', function (Blueprint $table) {
            Schema::dropIfExists('shared_medical_reports');
        });
    }
}
