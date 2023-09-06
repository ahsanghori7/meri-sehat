<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_record_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->foreign('medical_record_id')->references('id')->on('medical_records')->onDelete('cascade');
            $table->unsignedBigInteger('prescription_element_type_id');
            $table->foreign('prescription_element_type_id')->references('id')->on('prescription_element_types')->onDelete('cascade');
            $table->unsignedBigInteger('uploaded_by');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('file', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_record_files');
    }
}
