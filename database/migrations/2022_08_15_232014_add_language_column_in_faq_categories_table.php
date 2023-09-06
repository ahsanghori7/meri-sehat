<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageColumnInFaqCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->bigInteger('translation_of')->nullable()->unsigned()->index()->after('id');
            $table->bigInteger('lang_id')->default(1)->unsigned()->index()->after('translation_of');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropIndex('translation_of');
            $table->dropIndex('lang_id');
            $table->dropColumn('translation_of');
            $table->dropColumn('lang_id');
        });
    }
}
