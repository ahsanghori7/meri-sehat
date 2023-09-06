<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->string('home_type', 255)->nullable()->after('card_color');
            $table->string('card_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_1_color', 20)->nullable()->after('card_color');
            $table->string('card_1_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_2_color', 20)->nullable()->after('card_color');
            $table->string('card_2_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_3_color', 20)->nullable()->after('card_color');
            $table->string('card_3_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_4_color', 20)->nullable()->after('card_color');
            $table->string('card_4_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_5_icon', 255)->nullable()->after('card_color');
            $table->string('card_5_head', 255)->nullable()->after('card_color');
            $table->text('card_5_desc', 255)->nullable()->after('card_color');
            $table->string('card_5_link', 255)->nullable()->after('card_color');
            $table->string('card_5_color', 20)->nullable()->after('card_color');
            $table->string('card_5_inner_color', 20)->nullable()->after('card_color');

            $table->string('card_6_icon', 255)->nullable()->after('card_color');
            $table->string('card_6_head', 255)->nullable()->after('card_color');
            $table->text('card_6_desc', 255)->nullable()->after('card_color');
            $table->string('card_6_link', 255)->nullable()->after('card_color');
            $table->string('card_6_color', 20)->nullable()->after('card_color');
            $table->string('card_6_inner_color', 20)->nullable()->after('card_color');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->dropColumn('card_inner_color');

            $table->dropColumn('card_1_color');
            $table->dropColumn('card_1_inner_color');

            $table->dropColumn('card_2_color');
            $table->dropColumn('card_2_inner_color');

            $table->dropColumn('card_3_color');
            $table->dropColumn('card_3_inner_color');

            $table->dropColumn('card_4_color');
            $table->dropColumn('card_4_inner_color');

            $table->dropColumn('card_5_icon');
            $table->dropColumn('card_5_head');
            $table->dropColumn('card_5_desc');
            $table->dropColumn('card_5_link');
            $table->dropColumn('card_5_color');
            $table->dropColumn('card_5_inner_color');

            $table->dropColumn('card_6_icon');
            $table->dropColumn('card_6_head');
            $table->dropColumn('card_6_desc');
            $table->dropColumn('card_6_link');
            $table->dropColumn('card_6_color');
            $table->dropColumn('card_6_inner_color');

        });
    }
};
