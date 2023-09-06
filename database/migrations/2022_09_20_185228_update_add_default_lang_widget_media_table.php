<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddDefaultLangWidgetMediaTable extends Migration
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
            $table->boolean('is_default')->default(1)->after('source');
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
            $table->dropColumn('is_default');
        });
    }
}
