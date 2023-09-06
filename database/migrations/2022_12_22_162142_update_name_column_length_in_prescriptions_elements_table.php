<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNameColumnLengthInPrescriptionsElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prescription_elements', function (Blueprint $table) {
            $table->string('name', 250)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_elements', function (Blueprint $table) {
            $table->string('name', 250)->change();
        });
    }
}
