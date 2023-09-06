<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInPrescriptionElementTypeCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescription_element_type_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('prescription_element_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_element_type_categories', function (Blueprint $table) {
            $table->dropColumn('prescription_element_id');
            $table->dropColumn('doctor_id');
        });
    }
}
