<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWidgetCallByReferenceCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->string('card_type', 255)->nullable()->after('card_color');
            $table->string('sub_head', 255)->nullable()->after('card_color');
            $table->string('card_1_icon', 255)->nullable()->after('card_color');
            $table->string('card_1_head', 255)->nullable()->after('card_color');
            $table->text('card_1_desc', 255)->nullable()->after('card_color');
            $table->string('card_1_link', 255)->nullable()->after('card_color');
            $table->string('card_2_icon', 255)->nullable()->after('card_color');
            $table->string('card_2_head', 255)->nullable()->after('card_color');
            $table->text('card_2_desc', 255)->nullable()->after('card_color');
            $table->string('card_2_link', 255)->nullable()->after('card_color');
            $table->string('card_3_icon', 255)->nullable()->after('card_color');
            $table->string('card_3_head', 255)->nullable()->after('card_color');
            $table->text('card_3_desc', 255)->nullable()->after('card_color');
            $table->string('card_3_link', 255)->nullable()->after('card_color');
            $table->string('card_4_icon', 255)->nullable()->after('card_color');
            $table->string('card_4_head', 255)->nullable()->after('card_color');
            $table->text('card_4_desc', 255)->nullable()->after('card_color');
            $table->string('card_4_link', 255)->nullable()->after('card_color');
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
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->dropColumn('card_type');
            $table->dropColumn('sub_head');
            $table->dropColumn('card_1_icon');
            $table->dropColumn('card_1_head');
            $table->dropColumn('card_1_desc');
            $table->dropColumn('card_1_link');
            $table->dropColumn('card_2_icon');
            $table->dropColumn('card_2_head');
            $table->dropColumn('card_2_desc');
            $table->dropColumn('card_2_link');
            $table->dropColumn('card_3_icon');
            $table->dropColumn('card_3_head');
            $table->dropColumn('card_3_desc');
            $table->dropColumn('card_3_link');
            $table->dropColumn('card_4_icon');
            $table->dropColumn('card_4_head');
            $table->dropColumn('card_4_desc');
            $table->dropColumn('card_4_link');
        });
    }
}
