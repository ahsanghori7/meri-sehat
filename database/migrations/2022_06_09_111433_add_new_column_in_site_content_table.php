<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInSiteContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_content', function (Blueprint $table) {
            $table->bigInteger('translation_of')->nullable()->unsigned()->index()->after('id');
            $table->unsignedBigInteger('lang_id')->after('translation_of');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
            $table->dropColumn('language');
            $table->longText('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('site_content', function (Blueprint $table) {
        //     $table->dropColumn('translation_of');
        //     $table->dropColumn('lang_id');
        // });
    }
}
