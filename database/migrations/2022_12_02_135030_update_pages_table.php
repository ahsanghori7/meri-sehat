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
        //
        Schema::table('pages', function (Blueprint $table) {
            //
            $table->string('home_image')->nullable();
            $table->boolean('hide_home_image_in_detail')->default(true);
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
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('home_image');
            $table->dropColumn('hide_home_image_in_detail');
        });
    }
};
