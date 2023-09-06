<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddWebReferenceWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('reference_widgets', function (Blueprint $table) {
            $table->boolean('is_web_show')->default(true)->after('status');
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
        Schema::table('reference_widgets', function (Blueprint $table) {
            $table->dropColumn('is_web_show');
        });
    }
}
