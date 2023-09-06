<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallNotesReasonAdditionalDetailInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->longText('call_notes')->default(null);
            $table->string('reason_for_visit')->default(null);
            $table->longText('additional_detail')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('call_notes');
            $table->dropColumn('reason_for_visit');
            $table->dropColumn('additional_detail');
        });
    }
}
