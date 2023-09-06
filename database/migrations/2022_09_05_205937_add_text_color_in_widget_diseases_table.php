<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextColorInWidgetDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_diseases', function (Blueprint $table) {
            $table->string('text_color')->nullable()->default('#000000')->after('sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widget_diseases', function (Blueprint $table) {
            $table->dropColumn('text_color');
        });
    }
}
