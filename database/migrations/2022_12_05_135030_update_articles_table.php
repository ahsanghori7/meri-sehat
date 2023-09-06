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
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_app_show')->default(true);
            $table->boolean('is_web_show')->default(true);
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
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('is_app_show');
            $table->dropColumn('is_web_show');
        });
    }
};
