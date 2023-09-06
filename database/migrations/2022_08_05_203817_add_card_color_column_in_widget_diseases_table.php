<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardColorColumnInWidgetDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_diseases', function (Blueprint $table) {
            $table->string('card_color', 100)->nullable()->after('sequence');
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
            $table->dropColumn('card_color');
        });
    }
}
