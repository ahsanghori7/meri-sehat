<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBloodGroupInPrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->text('patient_consultation_note')->nullable()->after('cosultation_note')->change();
            $table->text('blood_group')->nullable()->after('cosultation_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->text('patient_consultation_note')->nullable()->after('updated_at')->change();
            $table->dropColumn('blood_group');
        });
    }
}
