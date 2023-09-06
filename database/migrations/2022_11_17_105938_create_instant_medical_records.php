<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstantMedicalRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE TABLE instant_medical_records LIKE medical_records; ');
        \DB::statement('CREATE TABLE instant_medical_record_files LIKE medical_record_files; ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instant_medical_records');
        Schema::dropIfExists('instant_medical_record_files');
    }
}
