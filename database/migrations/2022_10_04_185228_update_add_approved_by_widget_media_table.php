<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddApprovedByWidgetMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('widget_media', function (Blueprint $table) {
            $table->bigInteger('approved_by')->unsigned()->nullable()->after('source');
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
        Schema::table('widget_media', function (Blueprint $table) {
            $table->dropColumn('approved_by');
        });
    }
}
