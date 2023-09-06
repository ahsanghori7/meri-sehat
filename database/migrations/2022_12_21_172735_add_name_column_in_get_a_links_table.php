<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameColumnInGetALinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('get_a_links', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('page_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('get_a_links', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('page_title');
        });
    }
}
