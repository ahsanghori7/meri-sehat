<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInInfoModalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('info_modal', function (Blueprint $table) {
            $table->bigInteger('translation_of')->nullable()->unsigned()->index()->after('id');
            $table->unsignedBigInteger('lang_id')->after('translation_of');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
            $table->boolean('status', 50)->default(true)->after('content');
            $table->dropColumn('language');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_modal', function (Blueprint $table) {
            // $table->dropForeign('translation_of');
            // $table->dropForeign('lang_id');
        });
    }
}
