<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdColumnInWidgetCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_cards', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable()->unsigned()->index()->after('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widget_cards', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
